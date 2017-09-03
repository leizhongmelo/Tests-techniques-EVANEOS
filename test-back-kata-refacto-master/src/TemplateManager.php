<?php

class TemplateManager
{
  //Template:objet
  public function getTemplateComputed(Template $tpl, array $data)
  {
    if (!$tpl) {
      throw new \RuntimeException('no tpl given');
    }
    //if objet exist, call the private computeText function, replace text
    $replaced = clone($tpl);
    $replaced->subject = $this->computeText($replaced->subject, $data);
    $replaced->content = $this->computeText($replaced->content, $data);

    return $replaced;
  }

  //creat a function for QuoteEntity
  public function placeQuote($text)
  {
    //stock '[quote:summary_html]' and '[quote:summary]'
    $containsSummaryHtml = strpos($text, '[quote:summary_html]');
    $containsSummary     = strpos($text, '[quote:summary]');

    if ($containsSummaryHtml !== false) {
      $text = str_replace('[quote:summary_html]',Quote::renderHtml($_quoteFromRepository),$text);
    }

    if ($containsSummary !== false) {
      $text = str_replace('[quote:summary]',Quote::renderText($_quoteFromRepository),$text);
    }
    //return $text for change
    return $text;
  }

  //creat a function for DestionationEntity
  public function placeDestination($text,$quote)
  {
    $usefulObject = SiteRepository::getInstance()->getById($quote->siteId);
    $destinationOfQuote = DestinationRepository::getInstance()->getById($quote->destinationId);

    if(strpos($text, '[quote:destination_name]') !== false){
      $text = str_replace('[quote:destination_name]',$destinationOfQuote->countryName,$text);
      //var_dump($destinationOfQuote->countryName);=> for test
    }

    return $text;
  }

  //creat a function for userEntity
  public function placeUser($text,$_user){
    if($_user) {
      if(strpos($text, '[user:first_name]') !== false){
        $text = str_replace('[user:first_name]', ucfirst(mb_strtolower($_user->firstname)), $text);
        //mb_strtolower: return all letters in mini
        //ucfist: return the fist lettre in majr
      }
    }
    return $text;
  }

  //refactor
  private function computeText($text, array $data)
  {
    //initialize
    $APPLICATION_CONTEXT = ApplicationContext::getInstance();

    $quote = (isset($data['quote']) and $data['quote'] instanceof Quote) ? $data['quote'] : null;
    $_user  = (isset($data['user'])  and ($data['user']  instanceof User))  ? $data['user']  : $APPLICATION_CONTEXT->getCurrentUser();

    if ($quote)
    {
      $_quoteFromRepository = QuoteRepository::getInstance()->getById($quote->id);
        
      //call public functions
      $text = $this->placeQuote($text);
      $text = $this->placeDestination($text, $quote);
      $text = $this->placeUser($text,$_user);

      //return all changes
      return $text;
    }
  }
}
