<?php

namespace App\Services\Repositories;

use PDO;
use App\Services\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\Input\SignUpForm;
use App\Models\User;
use App\Services\Helpers\DateTimeManager;
use App\Exceptions\AlreadyExistingDbRecordException;
use App\Exceptions\NotFoundUserException;
use Exception;

/**
 * Description of UserRepository
 *
 * @author Hristo
 */
class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function create(SignUpForm $input): User
    {
        $user = new User();
        $user->first_name = $input->getFirstName();
        $user->last_name = $input->getLastName();
        $user->email = $input->getEmail();
        $user->password_hash = password_hash($input->getPassword(), PASSWORD_DEFAULT);
        $user->created_at = DateTimeManager::nowStr();
        $user->updated_at = DateTimeManager::nowStr();

        $user->validate();

        if (null !== $this->getByEmail($user->email)) {
            throw new AlreadyExistingDbRecordException('This e-mail is already in use!');
        }

        $insert_query = "INSERT INTO `user` (`first_name`, `last_name`, `email`, `password_hash`, `created_at`,
            `updated_at`)
            VALUES (:first_name, :last_name, :email, :password_hash, :created_at, :updated_at)";

        $insert_stmt = $this->dbConnection->prepare($insert_query);

        // DATA BINDING
        $insert_stmt->bindValue(':first_name', htmlspecialchars(strip_tags($user->first_name)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':last_name', htmlspecialchars(strip_tags($user->last_name)), PDO::PARAM_STR);
        $insert_stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
        $insert_stmt->bindValue(':password_hash', $user->password_hash, PDO::PARAM_STR);
        $insert_stmt->bindValue(':created_at', $user->created_at, PDO::PARAM_STR);
        $insert_stmt->bindValue(':updated_at', $user->updated_at, PDO::PARAM_STR);

        $insert_stmt->execute();

        $user->id = $this->dbConnection->lastInsertId();

        $user->validateDbRecord();

        return $user;
    }

    public function getById(int $id): ?User
    {
        $sql = "SELECT * FROM `user` WHERE id={$id}";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchObject(User::class);
        }

        return null;
    }

    public function getByEmail(string $email): ?User
    {
        $check_email = "SELECT * FROM `user` WHERE `email`=:email";
        $stmt = $this->dbConnection->prepare($check_email);
        $stmt->bindValue(':email', trim($email), PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchObject(User::class);
        }

        return null;
    }

    /**
     * Returns all the data from user table, but without password_hash column.
     *
     * @return array User[]
     */
    public function getAllOrderByIdPublic(): array
    {
        $sql = "SELECT 
                `id`,
                `first_name`,
                `last_name`,
                `email`,
                '' AS `password_hash`,
                `created_at`,
                `updated_at`
            FROM `user` ORDER BY `id`";
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, User::class);
        }

        return [];
    }

    public function update(User $user): User
    {
        $user->validateDbRecord();

        $update_query = "UPDATE `user` SET
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            password_hash = :password_hash,
            updated_at = :updated_at 
        WHERE id = :id";

        $update_stmt = $this->dbConnection->prepare($update_query);

        $update_stmt->bindValue(':first_name', htmlspecialchars(strip_tags($user->first_name)), PDO::PARAM_STR);
        $update_stmt->bindValue(':last_name', htmlspecialchars(strip_tags($user->last_name)), PDO::PARAM_STR);
        $update_stmt->bindValue(':email', $user->email, PDO::PARAM_STR);
        $update_stmt->bindValue(':password_hash', $user->password_hash, PDO::PARAM_STR);
        $update_stmt->bindValue(':updated_at', $user->updated_at, PDO::PARAM_STR);
        $update_stmt->bindValue(':id', $user->id, PDO::PARAM_INT);


        if ($update_stmt->execute() && $user->validateDbRecord()) {
            return $user;
        }

        throw new Exception('Not updated User record id: ' . $user->id . '.');
    }

    public function delete(User $user): void
    {
        $user->validateDbRecord();

        // No such DB User record.
        if (null === $this->getById($user->id)) {
            throw new NotFoundUserException('User with id: ' . $user->id . ' does not exists and cannot be deleted.');
        }

        $delete_post = "DELETE FROM `user` WHERE id=:id";
        $deleteStmt = $this->dbConnection->prepare($delete_post);
        $deleteStmt->bindValue(':id', $user->id, PDO::PARAM_INT);

        if (!$deleteStmt->execute()) {
            throw new Exception('Not deleted User record id: ' . $user->id . '.');
        }
    }
}
