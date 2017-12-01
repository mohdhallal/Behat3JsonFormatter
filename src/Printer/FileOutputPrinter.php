<?php

/**
 * @author George Tarkalanov
 * Date: 1.12.17
 * Time: 14:10
 */

namespace gturkalanov\Behat3JsonExtension\Printer;

use Behat\Testwork\Output\Printer\OutputPrinter as PrinterInterface;

class FileOutputPrinter implements PrinterInterface
{
    /**
     * @var
     */
    private $outputPath;

    /**
     * Sets output path.
     *
     * @param string $path
     */
    public function setOutputPath($path)
    {
        // TODO: Implement setOutputPath() method.
    }

    /**
     * Returns output path.
     *
     * @return null|string
     *
     * @deprecated since 3.1, to be removed in 4.0
     */
    public function getOutputPath()
    {
        return $this->outputPath;
    }

    /**
     * Sets output styles.
     *
     * @param array $styles
     */
    public function setOutputStyles(array $styles)
    {
        // TODO: Implement setOutputStyles() method.
    }

    /**
     * Returns output styles.
     *
     * @return array
     *
     * @deprecated since 3.1, to be removed in 4.0
     */
    public function getOutputStyles()
    {
        // TODO: Implement getOutputStyles() method.
    }

    /**
     * Forces output to be decorated.
     *
     * @param Boolean $decorated
     */
    public function setOutputDecorated($decorated)
    {
        // TODO: Implement setOutputDecorated() method.
    }

    /**
     * Returns output decoration status.
     *
     * @return null|Boolean
     *
     * @deprecated since 3.1, to be removed in 4.0
     */
    public function isOutputDecorated()
    {
        return true;
    }

    /**
     * Sets output verbosity level.
     *
     * @param integer $level
     */
    public function setOutputVerbosity($level)
    {
        // TODO: Implement setOutputVerbosity() method.
    }

    /**
     * Returns output verbosity level.
     *
     * @return integer
     *
     * @deprecated since 3.1, to be removed in 4.0
     */
    public function getOutputVerbosity()
    {
        // TODO: Implement getOutputVerbosity() method.
    }

    /**
     * Writes message(s) to output stream.
     *
     * @param string|array $messages message or array of messages
     */
    public function write($messages)
    {
        // TODO: Implement write() method.
    }

    /**
     * Writes newlined message(s) to output stream.
     *
     * @param string|array $messages message or array of messages
     */
    public function writeln($messages = '')
    {
        // TODO: Implement writeln() method.
    }

    /**
     * Clear output stream, so on next write formatter will need to init (create) it again.
     */
    public function flush()
    {
        // TODO: Implement flush() method.
    }
}