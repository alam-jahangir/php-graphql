<?php
/**
 * Created by PhpStorm.
 * User: jahangir<jahangir033003@gmail.com>
 * Date: 4/18/19
 * Time: 3:55 PM
 */
namespace Test\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UserType
{
    public static function query($type)
    {
        return new ObjectType([
            'name' => $type,
            'description' => 'User Information',
            'fields' => function() {
                return [
                    'id' => Type::id(),
                    'email' => Type::string(),
                    'name' => Type::string(),
                    'full_name' => Type::string(),
                    'created_at' => Type::string(),
                    'updated_at' => Type::string(),
                    'fieldWithError' => [
                        'type' => Type::string(),
                        'resolve' => function() {
                            throw new \Exception("This is error field");
                        }
                    ]
                 ];
            }
        ]);

    }

}