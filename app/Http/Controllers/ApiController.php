<?php namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use Validator;
use \Illuminate\Http\Response as IlluminateResponse;

class ApiController extends Controller {

  protected $statusCode;
  /**
  * Get Status Code
  *
  * @param
  * @return
  */
  public function getStatusCode()
  {
      return $this->statusCode;
  }
  /**
  * Set Status Code
  *
  * @param
  * @return $this
  */
  public function setStatusCode($statusCode)
  {
    $this->statusCode = $statusCode;

    return $this;
  }
  /**
  * Respond Not Found (404)
  *
  * @param
  * @return $this
  */
  public function respondNotFound($message = 'Not Found!')
  {
    // return
    $this->setStatusCode(404)->respondWithError($message);

  }
  /**
  * Respond With Json
  *
  * @param
  * @return Json
  */
  public function respond($data, $headers = [])
  {
    return Response::json($data, $this->getStatusCode(), $headers);
  }
  /**
  * Respond With Error
  *
  * @param
  * @return $this
  */
  public function respondWithError($message)
  {
    return $this->respond([
      'error' => [
        'message' => $message,
        'status_code' => $this->getStatusCode()
      ]
    ]);
  }
  /**
  * Respond With Success
  *
  * @param
  * @return $this
  */
  public function respondWithSuccess($message)
  {
    return $this->respond([
      'success' => [
        'message' => $message,
        'status_code' => $this->getStatusCode()
      ]
    ]);
  }
  /**
  * Respond Internal Error
  *
  * @param
  * @return $this message with status code
  */
  public function respondInternalError($message = 'Internal Error' )
  {
    return $this->setStatusCode(500)->respondWithError($message);
  }
  /**
  * Respond Created
  *
  * @param
  * @return
  */
  public function respondCreated($message)
  {
    return $this->setStatusCode(201)->respond([
			'status' => 'success',
			'message' => $message
		]);
  }
  /**
  * Respond Validation
  *
  * @param
  * @return
  */
  public function respondValidationError($message, $errors)
  {
    return $this->setStatusCode(IlluminateResponse::HTTP_UNPROCESSABLE_ENTITY)->respond([
      'errors' => $errors,
			'message' => $message
		]);
  }
  /**
  * Respond Unauthorized
  *
  * @param
  * @return
  */
  public function respondUnauthorisedError($message, $error = "Invalid Credentials.")
  {
    return $this->setStatusCode(IlluminateResponse::HTTP_UNAUTHORIZED)->respond([
      'errors' => $error,
			'message' => $message
		]);
  }
}

?>
