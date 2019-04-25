<?php
/**
 * Created by PhpStorm.
 * User: jahangir<jahangir033003@gmail.com>
 * Date: 4/18/19
 * Time: 3:56 PM
 */
require_once __DIR__ . '/vendor/autoload.php';
use GraphQL\Type\Schema;
use GraphQL\GraphQL;
use Test\DB;
use Test\Type\QueryType;

$httpStatus = 200;
try {

    //database connection configuration
    $config = [
        'host' => 'DB_HOST',
        'database' => 'DB_NAME',
        'username' => 'DB_USER',
        'password' => 'DB_PASSWORD'
    ];

    //initialisation of database connection
    DB::init($config);

    /*WE WILL FILL THIS SPACE LATER*/



    // See docs on schema options:
    // http://webonyx.github.io/graphql-php/type-system/schema/#configuration-options
    $schema = new Schema([
        'query' => QueryType::query(),
        'mutation' => QueryType::mutation(),
        'subscription' => QueryType::subscription()
    ]);

    //gets the root of the sent json {"query":"query{accidentsData(...)}"}
    $rawInput = file_get_contents('php://input');
    //decodes the content as JSON
    $input = json_decode($rawInput, true);
    //takes the "query" property of the object
    $query = isset($input['query']) ? $input['query'] : null;
    if (null === $query) {
        $query = '{hello}';
    }
    //checks if the input variables are a set
    $variableValues = isset($input['variables']) ? $input['variables'] : null;
    //calls the graphQL PHP libraty execute query with the prepared variables
    $result = GraphQL::executeQuery($schema, $query, null, null, $variableValues);

    //converts the result to a PHP array
    $output = $result->toArray();
} catch (\Exception $e) {
    $httpStatus = 400;
    //creates the error message in case it goes wrong
    $output = [
        'error' => [
            'message' => $e->getMessage()
        ]
    ];
}


header('Content-Type: application/json', true, $httpStatus);
echo json_encode($output);
