<?php
namespace Framework{
    require_once('autoloader.php');
    //use Framework as Framework;
    //use Framework\Test as Test;

    ////////////// Tests Cache
    Test::add(

        function(){
            $cache = new Cache();
            return ($cache instanceof Cache);
        },
        'Cache instantiates in uninitialized state',
        'Cache'
    );

    Test::add(
        function(){
            $cache = new Cache(array('type'=>'memcached'));
            $cache = $cache->initialize();
            return ($cache instanceof Cache\Driver\Memcached);
        },
        'Cache\Driver\Memcached initializes',
        'Cache\Driver\Memcached'
    );

    Test::add(
        function(){
            $cache = new Cache(array('type'=>'memcached'));
            $cache = $cache->initialize();
            return ($cache->connect() instanceof Cache\Driver\Memcached);
        },
        'Cache\Driver\Memcached connects and returns itself',
        'Cache\Driver\Memcached'
    );

    Test::add(
        function(){
            $cache = new Cache(array('type'=>'memcached'));
            $cache = $cache->initialize();
            $cache = $cache->connect();
            $cache = $cache->disconnect();
            try{
                $cache->get('anything');
            }catch(Cache\Exception\Service $e){
                return ($cache instanceof Cache\Driver\Memcached);
            }
            return false;
        },
        'Cache\Driver\Memcached disconnects and returns itself',
        'Cache\Driver\Memcached'
    );

    Test::add(
        function(){
            $cache = new Cache(array('type'=>'memcached'));
            $cache = $cache->initialize();
            $cache = $cache->connect();
            return ($cache->set('foo','bar',1) instanceof Cache\Driver\Memcached);
        },
        'Cache\Driver\Memcached sets values and returns itself',
        'Cache\Driver\Memcached'
    );

    Test::add(
        function(){
            $cache = new Cache(array('type'=>'memcached'));
            $cache = $cache->initialize();
            $cache = $cache->connect();
            return ($cache->get('foo') == 'bar');
        },
        'Cache\Driver\Memcached retrieves values',
        'Cache\Driver\Memcached'
    );

    Test::add(
        function(){
            $cache = new Cache(array(
                "type" => "memcached"
            ));
            $cache = $cache->initialize();
            $cache = $cache->connect();
            return ($cache->get("404", "baz") == "baz");
        },
        'Cache\Driver\Memcached returns default values',
        'Cache\Driver\Memcached'
    );

    Test::add(
        function()
        {
            $cache = new Cache(array(
                "type" => "memcached"
            ));
            $cache = $cache->initialize();
            $cache = $cache->connect();
            sleep(1);
            return ($cache->get("foo") == null);
        },
        'Cache\Driver\Memcached expires values',
        'Cache\Driver\Memcached'
    );
    Test::add(
        function()
        {
            $cache = new Cache(array(
                "type" => "memcached"
            ));
            $cache = $cache->initialize();
            $cache = $cache->connect();
            $cache = $cache->set("hello", "world");
            $cache = $cache->erase("hello");
            return ($cache->get("hello") == null && $cache instanceof Cache\Driver\Memcached);
        },
        'Cache\Driver\Memcached erases values and returns self',
        'Cache\Driver\Memcached'
    );


    /////////////////Tests Configuration //////////////////////

    Test::add(
        function(){
            $configuration = new Configuration();
            return ($configuration instanceof Configuration);
        },
        'Configuration instantiates in uninitialized state',
        'Configuration'
    );

    Test::add(
        function(){
            $configuration = new Configuration(array('type'=>'ini'));
            $configuration = $configuration->initialize();
            return ($configuration instanceof Configuration\Driver\Ini);
        },
        'Configuration\Driver\Ini initializes',
        'Configuration\Driver\Ini'
    );

//    Test::add(
//        function(){
//            $configuration = new Configuration(array('type'=>'ini'));
//            $configuration = $configuration->initialize();
//            $parsed = $configuration->parse('_configuration');
//            return ($parsed['config']['first'] == 'hello' && $parsed['config']['second']['second'] == "bar");
//        },
//        'Configuration\Driver\Ini parses configuration files',
//        'Configuration\Driver\Ini'
//    );

    /////////////////// Database tests

    $options = array(
        'type'=>'mysql',
        'options'=>array(
            "host"=>"127.0.0.1"
            ,"username"=>"root"
            ,"password"=>""
            ,"schema"=>"prophpmvc"
            ,"port"=>"3306"
            ,"charset"=>"utf8"
        )
    );

    Test::add(
        function(){
            $database = new Database();
            return ($database instanceof Database);
        },
        'Database instantiates in uninitialized state',
        'Database'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            return ($database instanceof Database\Connector\MySql);
        },
        'Database\Connector\Mysql initializes',
        'Database\Connector\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            $database = $database->_connect();
            return ($database instanceof Database\Connector\MySql);
        },
        'Database\Connector\Mysql connects and returns self',
        'Database\Connector\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            $database = $database->_connect();
            $database = $database->_disconnect();
            try{
                $database->execute('SELECT 1');
            }catch(Database\Exception\Service $e){
                return ($database instanceof Database\Connector\MySql);
            }
            return false;
        },
        'Database\Connector\Mysql disconnects and returns self',
        'Database\Connector\Mysql'
    );

    Test::add(
        function() use ($options)
        {
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            return ($database->escape("foo'".'bar"') == "foo\\'bar\\\"");
        },
        'Database\Connector\Mysql escapes values',
        'Database\Connector\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            $database = $database->_connect();
            $database->execute("SOME INVALID SQL");
            return (bool) $database->lastError;
        },
        'Database\Connector\Mysql returns last error',
        'Database\Connector\Mysql'
    );

    Test::add(
        function() use ($options) {
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $database->execute("DROP TABLE IF EXISTS `tests`;");
            $database->execute("CREATE TABLE `tests`(
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `number` int(11) NOT NULL,
                    `text` varchar(255) NOT NULL,
                    `boolean` tinyint(4) NOT NULL,
                    PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
            ");
            return !$database->lastError;
        },
        'Database\Connector\Mysql executes queries',
        'Database\Connector\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            for ($i = 0; $i < 4; $i++){
                $database->execute("INSERT INTO `tests` (`number`, `text`, `boolean`) VALUES ('1337', 'text', '0');");
            }
            return $database->lastInsertId;
        },
        'Database\Connector\Mysql returns last inserted ID",',
        'Database\Connector\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $database->execute("UPDATE `tests` SET 'number' = 1338;");
            return $database->affectedRows;
        },
        'Database\Connector\Mysql returns affected rows',
        'Database\Connector\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $query = $database->query();
            return ($query instanceof Database\Query\Mysql);
        },
        'Database\Connector\Mysql returns instance of Database\Query\Mysql',
        'Database\Query\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $query = $database->query();
            return ($query->connector instanceof Database\Connector\Mysql);
        },
        'Database\Query\Mysql references connector',
        'Database\Query\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $row = $database->query()
                ->from("tests")
                ->first();
            return ($row["id"] == 1);
        },
        'Database\Query\Mysql fetches first row',
        'Database\Query\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $rows = $database->query()
                ->from("tests")
                ->all();
            return (count($rows) == 4);
        },
        'Database\Query\Mysql fetches multiple rows',
        'Database\Query\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $count = $database
                ->query()
                ->from("tests")
                ->count();
            return ($count == 4);
        },
        'Database\Query\Mysql fetches number of rows',
        'Database\Query\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $rows = $database->query()
                ->from("tests")
                ->limit(1, 2)
                ->order("id", "desc")
                ->all();
            return (count($rows) == 1 && $rows[0]["id"] == 3);
        },
        'Database\Query\Mysql accepts LIMIT, OFFSET, ORDER and DIRECTION clauses',
        'Database\Query\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $rows = $database->query()
                ->from("tests")
                ->where("id != ?", 1)
                ->where("id != ?", 3)
                ->where("id != ?", 4)
                ->all();
            return (count($rows) == 1 && $rows[0]["id"] == 2);
        },
        'Database\Query\Mysql accepts WHERE clauses',
        'Database\Query\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $rows = $database->query()
                ->from("tests", array(
                    "id" => "foo"
                ))
                ->all();
            return (count($rows) && isset($rows[0]["foo"]) && $rows[0]["foo"] == 1);
        },
        'Database\Query\Mysql can alias fields',
        'Database\Query\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $rows = $database->query()
                ->from("tests", array(
                    "tests.id" => "foo"
                ))
                ->join("tests AS baz", "tests.id = baz.id", array(
                    "baz.id" => "bar"
                ))
                ->all();
            return (count($rows) && $rows[0]['foo'] == $rows[0]['bar']);
        },
        'Database\Query\Mysql can join tables and alias joined fields',
        'Database\Query\Mysql'
    );

    Test::add(
        function() use ($options){
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $result = $database->query()
                ->from("tests")
                ->save(array(
                    "number" => 3,
                    "text" => "foo",
                    "boolean" => true
                ));
            return ($result == 5);
        },
        'Database\Query\Mysql can insert rows',
        'Database\Query\Mysql'
    );

    Test::add(
        function() use ($options)
        {
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $result = $database->query()
                ->from("tests")
                ->where("id = ?", 5)
                ->save(array(
                    "number" => 3,
                    "text" => "foo",
                    "boolean" => false
                ));
            return ($result == 0);
        },
        'Database\Query\Mysql can update rows',
        'Database\Query\Mysql'
    );
    Test::add(
        function() use ($options)
        {
            $database = new Database($options);
            $database = $database->initialize();
            //$database = $database->_connect();
            $database->query()
                ->from("tests")
                ->delete();
            return ($database->query()->from("tests")->count() == 0);
        },
        'Database\Query\Mysql can delete rows',
        'Database\Query\Mysql'
    );

    ///////////////// Model tests
    $database = new Database($options);
    $database = $database->initialize();
    Registry::set('database',$database);

    class Example extends Model{
        /**
         * @readwrite
         * @column
         * @type autonumber
         * @primary
         */
        protected $_id;
        /**
         * @readwrite
         * @column
         * @type text
         * @length 32
         */
        protected $_name;
        /**
         * @readwrite
         * @column
         * @type datetime
         */
        protected $_created;
    }

    Test::add(
        function() use ($database){
            return ($database->sync(new Example()) instanceof Database\Connector\Mysql);
        },
        'Model syncs',
        'Model'
    );

    Test::add(
        function(){
            $example = new Example(array(
                'name' => 'foo',
                'created' => date("Y-m-d H:i:s")
            ));
            return ($example->save()>0);
        },
        'Model inserts rows',
        'Model'
    );

    Test::add(
        function(){
            return (Example::count() == 1);
        },
        'Model fetches number of rows',
        'Model'
    );

    Test::add(
        function(){
            $example = new Example(array(
                'name' => 'foo',
                'created' => date("Y-m-d H:i:s")
            ));
            $example->save();
            $example->save();
            $example->save();
            return (Example::count() == 2);
        },
        'Model saves single row multiple times',
        'Model'
    );

    Test::add(
        function(){
            $example = new Example(array(
                'id' => 1,
                'name' => 'hello',
                'created' =>date("Y-m-d H:i:s")
            ));
            $example->save();
            return (Example::first()->name == 'hello');
        },
        'Model updates rows',
        'Model'
    );

    Test::add(
        function(){
            $example = new Example(array(
                'id' => 2,
            ));
            $example->delete();
            return (Example::count() == 1);
        },
        'Model deletes rows',
        'Model'
    );
    /////////////// Templates test

    $template = new Template(array(
        "implementation" => new Template\Implementation\Standard()
    ));

    Test::add(
        function() use ($template)
        {
            return ($template instanceof Template);
        },
        "Template instantiates",
        "Template"
    );

    Test::add(
        function() use ($template)
        {
            $template->parse("{echo 'hello world'}");
            $processed = $template->process();

            return ($processed == "hello world");
        },
        "Template parses echo tag",
        "Template"
    );

    Test::add(
        function() use ($template)
        {
            $template->parse("{script \$_text[] = 'foo bar' }");
            $processed = $template->process();

            return ($processed == "foo bar");
        },
        "Template parses script tag",
        "Template"
    );

    Test::add(
        function() use ($template)
        {
            $template->parse("
            {foreach \$number in \$numbers}{echo \$number_i},{echo \$number},{/foreach}"
            );
            $processed = $template->process(array(
                "numbers" => array(1, 2, 3)
            ));

            return (trim($processed) == "0,1,1,2,2,3,");
        },
        "Template parses foreach tag",
        "Template"
    );

    Test::add(
        function() use ($template)
        {
            $template->parse("
            {for \$number in \$numbers}{echo \$number_i},{echo \$number},{/for}
        ");
            $processed = $template->process(array(
                "numbers" => array(1, 2, 3)
            ));

            return (trim($processed) == "0,1,1,2,2,3,");
        },
        "Template parses for tag",
        "Template"
    );

    Test::add(
        function() use ($template)
        {
            $template->parse("
            {if \$check == \"yes\"}yes{/if}
            {elseif \$check == \"maybe\"}yes{/elseif}
            {else}yes{/else}
        ");

            $yes = $template->process(array(
                "check" => "yes"
            ));

            $maybe = $template->process(array(
                "check" => "maybe"
            ));

            $no = $template->process(array(
                "check" => null
            ));

            return ($yes == $maybe && $maybe == $no);
        },
        "Template parses if, else and elseif tags",
        "Template"
    );

    Test::add(
        function() use ($template)
        {
            $template->parse("
            {macro foo(\$number)}
                {echo \$number + 2}
            {/macro}

            {echo foo(2)}
        ");
            $processed = $template->process();

            return ($processed == 4);
        },
        "Template parses macro tag",
        "Template"
    );

    Test::add(
        function() use ($template)
        {
            $template->parse("
            {literal}
                {echo \"hello world\"}
            {/literal}
        ");
            $processed = $template->process();

            return (trim($processed) == "{echo \"hello world\"}");
        },
        "Template parses literal tag",
        "Template"
    );

    //////////////////////////////
    $tests = Test::run();
    echo '<pre>';
    print_r($tests);
    echo '</pre>';
}


