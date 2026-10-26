<?php
declare(strict_types=1);
error_reporting(E_ALL);
set_error_handler(static function(int $severity,string $message,string $file,int $line): never {
    throw new ErrorException($message,0,$severity,$file,$line);
});
$autoload = $argv[1] ?? dirname(__DIR__).'/vendor/autoload.php';
$source = $argv[2] ?? null;
if ($source) require $source.'/src/Validate.php';
require $autoload;
$valid = [0,1,-1,PHP_INT_MAX,PHP_INT_MIN,'0','42','-42','+42',' 42 ',1.0,-1.0];
$invalid = [1.5,-1.5,0.1,'1.5','-1.5','1.0','1e3','01','0x10','NaN','Infinity',true,false,[],new stdClass(),INF,-INF,NAN,'9223372036854775808','-9223372036854775809'];
$results=[];
foreach (['integer','int'] as $rule) {
    foreach ([[true,$valid],[false,$invalid]] as [$expected,$values]) {
        foreach ($values as $i=>$value) {
            $validator=new think\Validate();
            try {
                $actual=$validator->is($value,$rule);
                $results[]=['rule'=>$rule,'group'=>$expected?'valid':'invalid','index'=>$i,'type'=>get_debug_type($value),'passed'=>$actual===$expected];
            } catch (Throwable $e) {
                $results[]=['rule'=>$rule,'group'=>$expected?'valid':'invalid','index'=>$i,'type'=>get_debug_type($value),'passed'=>false,'error'=>get_class($e)];
            }
        }
    }
    $validator=new think\Validate();
    $validator->rule(['data.id'=>'require|'.$rule]);
    $results[]=['rule'=>$rule,'scene'=>'nested-fraction','passed'=>!$validator->check(['data'=>['id'=>1.5]])];
    $results[]=['rule'=>$rule,'scene'=>'nested-integer','passed'=>$validator->check(['data'=>['id'=>'42']])];
}
$report=['php'=>PHP_VERSION,'mask'=>error_reporting(),'expected_mask'=>E_ALL,'source'=>(new ReflectionClass(think\Validate::class))->getFileName(),'total'=>count($results),'failed'=>count(array_filter($results,static fn($r)=>!$r['passed'])),'results'=>$results];
echo json_encode($report,JSON_PRETTY_PRINT|JSON_THROW_ON_ERROR),PHP_EOL;
exit($report['failed'] || error_reporting()!==E_ALL ? 1 : 0);
