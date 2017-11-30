<?php

namespace gturkalanov\Behat3JsonExtension\Formatter;

use Behat\Behat\EventDispatcher\Event as BehatEvent;
use Behat\Behat\Tester\Result;
use Behat\Testwork\EventDispatcher\Event as TestworkEvent;
use Behat\Behat\Tester\Result\ExecutedStepResult;
use Behat\Testwork\Counter\Memory;
use Behat\Testwork\Counter\Timer;
use webignition\JsonPrettyPrinter\JsonPrettyPrinter;



class Formatter implements FormatterInterface
{
    /**
     * @var
     */
    protected $json;

    /**
     * @var int
     */
    protected $stepCounter = 0;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var
*/
    private $timerScenario;

    /**
     * @var
     */
    private $timer;

    /**
     * @var
     */
    private $memory;

    /**
     * @var
     */
    private $printer;


    /**
     * @var
     */
    private $suites;

    /**
     * @var
     */
    private $currentSuite;

    /**
     * @var int
     */
    private $featureCounter = 1;

    /**
     * @var
     */
    private $features;

    /**
     * @var
     */
    private $currentScenario;

    /**
     * @var
     */
    private $failedScenarios;

    /**
     * @var
     */
    private $passedScenarios;

    /**
     * @var
     */
    private $currentFeature;

    /**
     * @var
     */
    private $failedFeatures;

    /**
     * @var
     */
    private $passedFeatures;

    /**
     * @var
     */
    private $failedSteps;

    /**
     * @var
     */
    private $passedSteps;

    /**
     * @var
     */
    private $pendingSteps;

    /**
     * @var
     */
    private $skippedSteps;

    /**
     * @var bool
     */
    protected $currentlyBackgroundUnderway;

    /**
     * @var array
     */
    protected $currentScenarios;

    /**
     * @var array
     */
    protected $currentSteps;

    /**
     * @var array
     */
    protected $currentStep;


    /**
     * @internal
     */
    public function __construct()
    {
        $this->timer = new Timer();
        $this->memory = new Memory();
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'tester.exercise_completed.before' => 'onBeforeExercise',
            'tester.exercise_completed.after' => 'onAfterExercise',
            'tester.suite_tested.before' => 'onBeforeSuiteTested',
            'tester.suite_tested.after' => 'onAfterSuiteTested',
            'tester.feature_tested.before' => 'onBeforeFeatureTested',
            'tester.feature_tested.after' => 'onAfterFeatureTested',
            'tester.scenario_tested.before' => 'onBeforeScenarioTested',
            'tester.scenario_tested.after' => 'onAfterScenarioTested',
            'tester.outline_tested.before' => 'onBeforeOutlineTested',
            'tester.outline_tested.after' => 'onAfterOutlineTested',
            'tester.step_tested.after' => 'onAfterStepTested',
        );
    }

    public function getDescription()
    {
        return 'Behat 3 Json Formatter';
    }

    public function getOutputPrinter()
    {
        return $this->printer;
    }

    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    public function setStepsCounter($counter)
    {
        $this->stepCounter += $counter;
    }

    public function getStepsCounter()
    {
        return $this->stepCounter;
    }

    public function getParameter($name)
    {
        return $this->parameters[$name];
    }

    public function getTimer()
    {
        return $this->timer;
    }

    public function getTimerScenario()
    {
        return $this->timerScenario;
    }

    public function getMemory()
    {
        return $this->memory;
    }

    public function getSuites()
    {
        return $this->suites;
    }

    public function getCurrentSuite()
    {
        return $this->currentSuite;
    }

    public function getFeatureCounter()
    {
        return $this->featureCounter;
    }

    public function getCurrentFeature()
    {
        return $this->currentFeature;
    }

    public function getCurrentScenario()
    {
        return $this->currentScenario;
    }

    public function getFailedScenarios()
    {
        return $this->failedScenarios;
    }

    public function getPassedScenarios()
    {
        return $this->passedScenarios;
    }

    public function getFailedFeatures()
    {
        return $this->failedFeatures;
    }

    public function getPassedFeatures()
    {
        return $this->passedFeatures;
    }

    public function getFailedSteps()
    {
        return $this->failedSteps;
    }

    public function getPassedSteps()
    {
        return $this->passedSteps;
    }

    public function getPendingSteps()
    {
        return $this->pendingSteps;
    }

    public function getSkippedSteps()
    {
        return $this->skippedSteps;
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
        $this->writeln($this->json);
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
        var_dump($json);
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
        $this->features[] = $this->currentFeature;
    }

    /**
     * @param BehatEvent\BeforeScenarioTested $event
     */
    public function onBeforeScenarioTested(BehatEvent\BeforeScenarioTested $event)
    {
        $scenario = $event->getScenario();
        $this->timerScenario = microtime(true);
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

    public function getName()
    {
        return 'json_formatter';
    }


    /**
     * @return string
     */
    protected function buildJson()
    {
        $json = json_encode(array(
            'date' => date('o-m-d H:i:s'),
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
