<?php declare(strict_types=1);

namespace App\Http\Controller;

use ReflectionException;
use Swoft;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Context\Context;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\Http\Server\Annotation\Mapping\RequestMethod;
use Swoft\Http\Message\Request;
use Swoft\View\Renderer;
use Throwable;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;

/**
 * Class GraphQLController
 * @Controller(prefix="/")
 */
class GraphQLController
{
    /**
     * @RequestMapping(route="graphql", method={RequestMethod::POST})
     * @throws Throwable
     */
    public function graphResponse(Request $request): Response
    {

        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'echo' => [
                    'type' => Type::string(),
                    'args' => [
                        'message' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => function ($root, $args) {
                        return self::getData();
                    }
                ],
            ],
        ]);
        
        
        $schema = new Schema([
            'query' => $queryType
        ]);
        
        $rawInput = $request->raw();
        $input = json_decode($rawInput, true);
        $query = $input['query'];
        $variableValues = isset($input['variables']) ? $input['variables'] : null;
        
        try {
            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
        } catch (\Exception $e) {
            $output = [
                'errors' => [
                    [
                        'message' => $e->getMessage()
                    ]
                ]
            ];
        }

        // return Context::mustGet()->getResponse()->withContentType(ContentType::JSON)->withContent(json_encode($output));
        return Context::mustGet()
            ->getResponse()
            ->withData($output);
    }

    public static function getData() 
    {
        return "Hello";
    }

}
