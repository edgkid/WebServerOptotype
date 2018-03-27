<?php
/**
 * Description of Diagnostic
 *
 * @author Edgar
 */
class Diagnostic {
    
    private $idSignalDefect;
    private $idSubjectiveTest;
    private $idObjectiveTest;
    private $idAntecedent;
    private $idAvResult;
    
    function getIdSignalDefect() {
        return $this->idSignalDefect;
    }

    function getIdSubjectiveTest() {
        return $this->idSubjectiveTest;
    }

    function getIdObjectiveTest() {
        return $this->idObjectiveTest;
    }

    function getIdAntecedent() {
        return $this->idAntecedent;
    }

    function getIdAvResult() {
        return $this->idAvResult;
    }

    function setIdSignalDefect($idSignalDefect) {
        $this->idSignalDefect = $idSignalDefect;
    }

    function setIdSubjectiveTest($idSubjectiveTest) {
        $this->idSubjectiveTest = $idSubjectiveTest;
    }

    function setIdObjectiveTest($idObjectiveTest) {
        $this->idObjectiveTest = $idObjectiveTest;
    }

    function setIdAntecedent($idAntecedent) {
        $this->idAntecedent = $idAntecedent;
    }

    function setIdAvResult($idAvResult) {
        $this->idAvResult = $idAvResult;
    }


}
