<?php

namespace App;

use SoapClient;

class OutboundAPI
{
  /**
   * Create a new SOAP interface instance
   * @return void
   */
  public function __construct()
  {
    $this->serviceUrl = 'https://omnc.outboundsoftware.com/api45/Service1.asmx?WSDL';
    try {
      $this->outboundApi = new SoapClient($this->serviceUrl);
    } catch (Exception $e) {
      $this->displayExeceptionMessage($e->getMessage());
    }
  }

  /**
   * Returns the events from today, through 7 days from now
   *
   * @return array
   */
  public function byWeek()
  {
    return $this->byDateRange(date('Y-m-d'), date('Y-m-d', strtotime("+7 days")));
  }

  /**
   * Returns the events from today, through 31 days from now
   *
   * @return array
   */
  public function byMonth()
  {
    return $this->byDateRange(date('Y-m-d'), date('Y-m-d', strtotime("+31 days")));
  }

  /**
   * Returns data for a single event by id
   *
   * @param integer $id
   * @return array
   */
  public function byId($id)
  {
    return json_decode(
      $this->outboundApi->GetActivityInformationJSON([
        'ActivityID' => $id
      ])->GetActivityInformationJSONResult, true)[0];
  }

  /**
   * Returns data for events by group / category ID
   *
   * @param integer $id
   * @return void
   */
  public function byGroupId($id)
  {
    return json_decode(
      $this->outboundApi->GetAllActivitiesByViewGroupJSON([
        'ViewGroupID' => $id
      ])->GetAllActivitiesByViewGroupJSONResult, true);
  }
  

  /**
   * Returns the data for usage in the calendar / list views. 
   * Start and End dates are optional. Defaults to today and 5 days if blank.
   *
   * @return array
   */
  public function byDateRange($startdate = null, $enddate = null)
  {

    $startdate = empty($startdate) ? date('Y-m-d') : $startdate;
    $enddate = empty($enddate) ? date('Y-m-d', strtotime("+5 days")) : $enddate;

    return json_decode(
      $this->outboundApi->GetActivityListJSON([
          'StartDate' => $startdate, 
          'EndDate' => $enddate
        ])->GetActivityListJSONResult, true);
  }

  public function allEvents($startdate = null)
  {
    $startdate = empty($startdate) ? date('Y-m-d') : $startdate;
    return json_decode($this->outboundApi->GetActivityListAllJSON(['StartDate' => $startdate])->GetActivityListAllJSONResult, true);
  }

  /**
   * Gets all group data for events
   *
   * @return array
   */
  public function eventGroups()
  {
    return json_decode($this->outboundApi->GetViewGroupListJSON()->GetViewGroupListJSONResult, true);
  }


  /**
   * Displays the execption from a failed Outbound API connection attempt
   *
   * @param [type] $message
   * @return void
   */
  private function displayExecptionMessage($message)
  {
    dd($message);
  }
}
