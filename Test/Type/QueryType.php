<?php
/**
 * Created by PhpStorm.
 * User: jahangir<jahangir033003@gmail.com>
 * Date: 4/18/19
 * Time: 3:56 PM
 */

namespace Test\Type;
use Test\DB;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ListOfType;


class QueryType
{
    public static function query() {

        return new ObjectType([
            'name' => 'Query',
            'fields' => [

                'getSpecificUser' => [
                    'description' => 'Get Specific User Information',
                    'args' => [
                        'id' => Type::nonNull(Type::id())
                    ],
                    'type' => UserType::query('GetSpecificUser'),
                    'resolve' => function ($root, $args, $context) {
                        $id = intval($args['id']);
                        $user = DB::fetch("SELECT * FROM users WHERE id=".$id);
                        return array(
                            'id' => $user->id,
                            'email' => $user->email,
                            'name' => $user->name,
                            'full_name' => $user->full_name,
                            'created_at' => $user->created_at,
                            'updated_at' => $user->updated_at
                        );
                    }
                ],

                'getUsers' => [
                    'description' => 'Get Users List',
                    'type' => new ListOfType(UserType::query('GetUsers')),
                    'resolve' => function ($root, $args, $context) {
                        $results = DB::fetchAll("SELECT * FROM users");
                        $users = array();
                        foreach ($results as $user) {
                            $users[] = array(
                                'id' => $user->id,
                                'email' => $user->email,
                                'name' => $user->name,
                                'full_name' => $user->full_name,
                                'created_at' => $user->created_at,
                                'updated_at' => $user->updated_at
                            );
                        }
                        return $users;
                    }
                ],

                'hello' => [
                    'type' => Type::string(),
                    'resolve' => function ($root, $args, $context) {
                        return self::hello();
                    }
                ]

            ]
        ]);

    }

    public static function mutation() {

        return new ObjectType([
           'name' => 'Mutation',
            'description' => 'Data Manipulation[Use id for Update]',
            'fields' => [
                'setUser' => [
                    'name' => 'setUser',
                    'type' => Type::string(),
                    'description' => 'Save User Information',
                    'args' => [
                        'id' => Type::id(),
                        'email' => Type::nonNull(Type::string()),
                        'name' => Type::nonNull(Type::string()),
                        'full_name' => Type::nonNull(Type::string()),
                        'password' => Type::nonNull(Type::string())
                    ],
                    'resolve' => function ($root, $args, $context) {
                        if (intval($args['id'])) {
                            $prepareStmt = "UPDATE`users` SET `email`=:email, `name`=:name, `full_name`=:full_name, `password`=:password, `updated_at`=current_timestamp
                                            WHERE id=".$args['id'];
                        } else {
                            $prepareStmt = "INSERT INTO `users` (`email`, `name`, `full_name`, `password`, `created_at`, `updated_at`) 
                                        VALUES (:email, :name, :full_name, :password, current_timestamp, current_timestamp)";
                        }
                        $data = array(
                            ':email' => $args['email'],
                            ':name' => $args['name'],
                            ':full_name' => $args['full_name'],
                            ':password' => md5($args['password']),
                        );
                        return DB::setData($prepareStmt, $data);
                    }
                ]
            ]
        ]);

    }

    public static function subscription() {

        return new ObjectType([
            'name' => 'Subscription',
            'description' => 'Subscribe Users',
            'fields' => [
                'setUserSubscribe' => [
                    'name' => 'setUserSubscribe',
                    'type' => Type::string(),
                    'description' => 'Save User Information',
                    'args' => [
                        'id' => Type::id(),
                        'email' => Type::nonNull(Type::string()),
                        'name' => Type::nonNull(Type::string()),
                        'full_name' => Type::nonNull(Type::string()),
                        'password' => Type::nonNull(Type::string())
                    ],

                    'resolve' => function ($root, $args, $context) {
                        /*if (intval($args['id'])) {
                            $prepareStmt = "UPDATE`users` SET `email`=:email, `name`=:name, `full_name`=:full_name, `password`=:password, `updated_at`=current_timestamp
                                            WHERE id=".$args['id'];
                        } else {
                            $prepareStmt = "INSERT INTO `users` (`email`, `name`, `full_name`, `password`, `created_at`, `updated_at`) 
                                        VALUES (:email, :name, :full_name, :password, current_timestamp, current_timestamp)";
                        }
                        $data = array(
                            ':email' => $args['email'],
                            ':name' => $args['name'],
                            ':full_name' => $args['full_name'],
                            ':password' => md5($args['password']),
                        );
                        return DB::setData($prepareStmt, $data);*/
                        return "Subscription is running";
                    }
                ]
            ]
        ]);

    }

    public static function hello()
    {
        return 'Your graphql-php endpoint is ready! Use GraphiQL to browse API';
    }
}