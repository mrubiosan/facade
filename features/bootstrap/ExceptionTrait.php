<?php
trait ExceptionTrait
{
    /**
     * @var \Exception
     */
    private $caughtException = null;
    
    /**
     * @Then A :type exception should have been thrown
     */
    public function anExceptionShouldHaveBeenThrown($type)
    {
        $this->assertExceptionHasBeenCaught($type);
    }
    
    /**
     * @Then A :type exception should have been thrown with message :message
     */
    public function anExceptionShouldHaveBeenThrownWithMessage($type, $message)
    {
        $this->assertExceptionHasBeenCaught($type, $message);
    }
    
    /**
     * @AfterScenario
     */
    public function rethrowNonClearedException()
    {
        if ($this->caughtException) {
            throw $this->caughtException;
        }
    }
    
    private function callAndCatchException(callable $call)
    {
        try {
            return $call();
        } catch(\Exception $e) {
            $this->caughtException = $e;
        }
    }
    
    private function assertExceptionHasBeenCaught($exceptionType = 'Exception', $message = null)
    {
        $rightException = $this->caughtException instanceof $exceptionType;
        assert($rightException, "Caught exception is null or not an instance of Exception");
        
        if (isset($message)) {
            $actualExceptionMessage = $this->caughtException->getMessage();
            assert(
                 $actualExceptionMessage === $message,
                "Exception message should be '$message' but instead is '$actualExceptionMessage'"
            );
        }
        
        if ($rightException) {
            $this->caughtException = null;
        }
    }
}