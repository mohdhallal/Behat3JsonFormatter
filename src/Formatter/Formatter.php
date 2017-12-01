<?php

/**
 *
 * @author George Tarkalanov, <g.turkalanov@gmail.com>
 * Date: 1.12.17
 * Time: 13:46
 */

namespace gturkalanov\Behat3JsonExtension\Formatter;

use Behat\Testwork\Counter\Memory;
use Behat\Testwork\Counter\Timer;
use Behat\Testwork\EventDispatcher\Event as TestworkEvent;
use gturkalanov\Behat3JsonExtension\Printer\FileOutputPrinter;
use Behat\Behat\EventDispatcher\Event as BehatEvent;
use Behat\Behat\Tester\Result;
use Behat\Behat\Tester\Result\ExecutedStepResult;
use webignition\JsonPrettyPrinter\JsonPrettyPrinter;


class Formatter implements FormatterInterface
{
    /**
     * @var
     */
    private $printer;
    /**
     * @var
     */
    private $parameters;
    /**
     * @var
     */
    private $json;
    /**
     * @var
     */
    private $memory;
    /**
     * @var
     */
    private $timerScenario;
    /**
     * @var
     */
    private $currentFeature;
    /**
     * @var
     */
    private $currentScenarios;
    /**
     * @var
     */
    private $features;
    /**
     * @var int
     */
    private $featureCounter =0;
    /**
     * @var
     */
    private $currentScenario;
    /**
     * @var
     */
    private $currentSteps;
    /**
     * @var int
     */
    private $stepCounter = 0;
    /**
     * @var Timer
     */
    private $timer;

    /**
     *
     */
    function __construct(){
        $this->printer = new FileOutputPrinter();
        $this->timer = new Timer();
        $this->memory = new Memory();
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return array(
            'tester.exercise_completed.before' => 'onBeforeExercise',
            'tester.exercise_completed.after'  => 'onAfterExercise',
            'tester.suite_tested.before'       => 'onBeforeSuiteTested',
            'tester.suite_tested.after'        => 'onAfterSuiteTested',
            'tester.feature_tested.before'     => 'onBeforeFeatureTested',
            'tester.feature_tested.after'      => 'onAfterFeatureTested',
            'tester.scenario_tested.before'    => 'onBeforeScenarioTested',
            'tester.scenario_tested.after'     => 'onAfterScenarioTested',
            'tester.outline_tested.before'     => 'onBeforeOutlineTested',
            'tester.outline_tested.after'      => 'onAfterOutlineTested',
            'tester.step_tested.after'         => 'onAfterStepTested',
        );
    }

    /**
     * Returns formatter name.
     *
     * @return string
     */
    public function getName()
    {
        return "json_formatter";
    }

    /**
     * Returns formatter description.
     *
     * @return string
     */
    public function getDescription()
    {
        return "Behat 3 Json formatter";
    }

    /**
     * Returns formatter output printer.
     *
     * @return \Behat\Testwork\Output\Printer\OutputPrinter
     */
    public function getOutputPrinter()
    {
        return $this->printer;
    }

    /**
     * Sets formatter parameter.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setParameter($name, $value)
    {
        $this->parameters[ $name ] = $value;
    }

    /**
     * Returns parameter name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->parameters[ $name ];
    }

    public function setStepsCounter($counter)
    {
        $this->stepCounter += $counter;
    }

    public function getStepsCounter()
    {
        return $this->stepCounter;
    }

    public function setFeatureCounter()
    {
        $this->featureCounter +=1;
    }

    public function getFeatureCounter()
    {
        return $this->featureCounter;
    }

    public function getTimer()
    {
        return $this->timer;
    }

    public function getMemory()
    {
        return $this->memory;
    }

    public function getTimerScenario()
    {
        return $this->timerScenario;
    }

    /**
     * Triggers before running tests.
     *
     * @param TestworkEvent\BeforeExerciseCompleted $event
     */
    public function onBeforeExercise(TestworkEvent\BeforeExerciseCompleted $event)
    {

    }

    /**
     * Triggers after running tests.
     *
     * @param TestworkEvent\AfterExerciseCompleted $event
     */
    public function onAfterExercise(TestworkEvent\AfterExerciseCompleted $event)
    {

    }

    /**
     * @param TestworkEvent\BeforeSuiteTested $event
     */
    public function onBeforeSuiteTested(TestworkEvent\BeforeSuiteTested $event)
    {

    }

    /**
     */
    public function onAfterSuiteTested()
    {
        $json = $this->buildJson();
        $json = $this->formatJson($json);
        $this->json = $json;
        echo $this->json."\n";
    }

    /**
     * @param BehatEvent\BeforeFeatureTested $event
     */
    public function onBeforeFeatureTested(BehatEvent\BeforeFeatureTested $event)
    {
        $this->timer = microtime(true);
        $feature = $event->getFeature();

        $this->currentFeature = array(
            'title' => $feature->getTitle(),
            'desc' => $feature->getDescription(),
            'tags' => $feature->getTags()
        );
        $this->currentScenarios = array();
    }

    /**
     * @param BehatEvent\AfterFeatureTested $event
     */
    public function onAfterFeatureTested(BehatEvent\AfterFeatureTested $event)
    {
        $stopFeature = microtime(true);
        if($event->getTestResult()->isPassed()){
            $this->currentFeature['result'] = 'passed';
        }
        else {
            $this->currentFeature['result'] = 'failed';
        }
        $this->currentFeature['duration'] = round($stopFeature - $this->getTimer(),2);
        $this->currentFeature['scenarios'] = $this->currentScenarios;
        $this->setFeatureCounter();
        $this->features[] = $this->currentFeature;
    }

    /**
     * @param BehatEvent\BeforeScenarioTested $event
     */
    public function onBeforeScenarioTested(BehatEvent\BeforeScenarioTested $event)
    {
        $this->timerScenario = microtime(true);
        $scenario = $event->getScenario();
        $this->currentScenario = array(
            'title' => $scenario->getTitle(),
            'isOutline' => false,
            'tags' => $scenario->getTags()
        );
        $this->currentSteps = array();
    }

    /**
     * @param BehatEvent\AfterScenarioTested $event
     */
    public function onAfterScenarioTested(BehatEvent\AfterScenarioTested $event)
    {
        $stopTime = microtime(true);
        $this->stepCounter = 0;
        if($event->getTestResult()->isPassed()){
            $this->currentScenario['result'] = 'passed';
        }
        else {
            $this->currentScenario['result'] = 'failed';
        }
        $this->currentScenario['duration'] = round($stopTime - $this->getTimerScenario(),2);
        var_dump($this->currentScenario['duration']);
        $this->currentScenario['steps'] = $this->currentSteps;
        $this->currentScenarios[] = $this->currentScenario;
    }

    /**
     * @param BehatEvent\BeforeOutlineTested $event
     */
    public function onBeforeOutlineTested(BehatEvent\BeforeOutlineTested $event)
    {

    }

    /**
     * @param BehatEvent\AfterOutlineTested $event
     */
    public function onAfterOutlineTested(BehatEvent\AfterOutlineTested $event)
    {

    }

    /**
     * @param BehatEvent\StepTested $event
     */
    public function onBeforeStepTested(BehatEvent\StepTested $event)
    {

    }


    /**
     * @param BehatEvent\AfterStepTested $event
     */
    public function onAfterStepTested(BehatEvent\AfterStepTested $event)
    {
        $result = $event->getTestResult();
        $stepCount = $this->getStepsCounter();
        switch($event->getTestResult()->getResultCode()){
            case 0: $this->currentSteps[$stepCount]['result'] = 'passed'; break;
            case 99: $this->currentSteps[$stepCount]['result'] = 'failed';
                if($result instanceof ExecutedStepResult) $this->currentSteps[$stepCount]['reason'] = $result->getException()->getMessage();
                break;
            case 10: $this->currentSteps[$stepCount]['result'] = 'skipped'; break;
        }
        $this->currentSteps[$stepCount]['type'] = $event->getStep()->getType();
        $this->currentSteps[$stepCount]['text'] = $event->getStep()->getText();
        $this->setStepsCounter(1);
    }

    /**
     * @return string
     */
    protected function buildJson()
    {
        $json = json_encode(array(
            'date' => date('o-m-d H:i:s'),
            'features_counter' => $this->getFeatureCounter(),
            'memory' => $this->getMemory()->__toString(),
            'features' => $this->features

        ));

        return $json;
    }

    /**
     * @param string $json
     *
     * @return string
     */
    protected function formatJson($json)
    {
        $formattedJson = $json;

        if ($this->getParameter('debug')) {
            $jsonPrettyPrinter = new JsonPrettyPrinter();
            $formattedJson = $jsonPrettyPrinter->format($json);
        }

        return $formattedJson;
    }
}