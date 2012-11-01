<?php

$file = __DIR__ . '/../vendor/autoload.php';
$loader = require $file;
$loader->add('\Midgard', __DIR__);
        
class my_mapper_class extends \Midgard\CreatePHP\Mapper\AbstractRdfMapper {
   
    public function store($object) {
        return true;
    }
    
    public function createSubject($object) {
        if (isset($object->id))
        {
            return $object->id;
        }
        return '';
    }
    
    public function getBySubject($subject) {
        return true;
    }
}

class my_delete_workflow_class implements \Midgard\CreatePHP\WorkflowInterface {
    public function getToolbarConfig($object)
    {
        return array
        (
            'name' => "mockbutton",
            'label' => 'Mock Label',
            'action' => array
            (
                'type' => "backbone_destroy"
            ),
            'type' => "button"
        );
    }

    public function run($object)
    {
        return array();
    }
    
}

class Article {
    public $id = 'id';
    public $title = 'title';
    public $content = 'content';
}

$object = new Article;
$mapper = new my_mapper_class;

$config = array
(
    'workflows' => array(
        'delete' => 'my_delete_workflow_class'
    ),
    'types' => array(
        'Article' => array(
            'config' => array(
                'storage' => 'some_db_table',
            ),
            'typeof' => 'sioc:Blog',
            'vocabularies' => array(
               'dcterms' => 'http://purl.org/dc/terms/',
               'sioc' => 'http://rdfs.org/sioc/ns#'
            ),
            'children' => array(
                'title' => array(
                    'property' => 'dcterms:title'
                ),
                'content' => array(
                    'property' => 'sioc:content'
                ),
            ),
        ),
    )
);

$loader = new Midgard\CreatePHP\ArrayLoader($config);
$manager = $loader->getManager($mapper);
$entity = $manager->getEntity($object);

?>

<!DOCTYPE html>
<html>
<head>
    <title>CreatePHP Demo Server - CONTENT CONTROL</title>
    <script type="text/javascript" src="create.js"></script>
</head>

<body>
    <?php echo $entity; ?>
</body>

</html>