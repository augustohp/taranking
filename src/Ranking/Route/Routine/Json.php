<?php
namespace Ranking\Route\Routine;

use \JSON_FORCE_OBJECT;

/**
 * Converts the given data into a valid JSON string.
 *
 * @author Augusto Pascutti <augusto@phpsp.org.br>
 */
class Json
{
    public function __invoke(array $data)
    {
        if (!headers_sent()) {
            header('Content-type: application/json');
        }
        unset($data['_view']);
        return json_encode($data);
    }
}