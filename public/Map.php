<?php

namespace App\Maps;

use Illuminate\Http\Request;
use App\Helpers\Objects\Caretaker;

use App\Maps\Planer\VoivodeshipMap;
use App\Maps\Planer\MaritalStatusMap;
use App\Maps\Planer\AcceptedIllnessMap;
use App\Maps\Planer\AcceptedMobilityMap;
use App\Maps\Planer\NeighborhoodMap;
use App\Maps\Planer\GenderMap;
use App\Maps\Planer\TrueFalseMap;
use App\Maps\Planer\HobbyMap;
use App\Maps\Planer\CharacterTraitsMap;
use App\Maps\Planer\LanguageMap;
use App\Maps\Planer\EmploymentTypeMap;
use App\Maps\Planer\MaxPatientsNumberMap;
use App\Maps\Planer\ActivityMap;
use App\Maps\Planer\RelativesCloseByMap;
use App\Maps\Planer\YesNoMap;
use App\Maps\Planer\SchoolEducationMap;

use Exception;
use App\Errors\ErrorHandler;
use App\Errors\Error;


class Map
{
    public static function set_from_array($class, $array)
    {
        $values = [];

        if (!is_array($array)) {
            if (array_key_exists($class, ErrorHandler::$map)) {
                Error::add($class, ErrorHandler::$map[$class]['map_error']);
                return null;
                // throw new Exception(ErrorHandler::$map[$class]['map_error']);
            } else {
                // throw new Exception('klasa ' . $class . ' tablica - ' . $array);
                Error::add('unexpected error', 'Nieoczekiwany błąd podczas przetwarzania danych.');
                return null;
            }
        }

        foreach ($array as $item) {
            $values[] = self::set_value($class, (int)$item);
        }

        return $values;
    }

    public static function set_value($class, $value, $return_as_array = false, $error_msg = null)
    {
        if (is_null($value) || $value === '') {
            return "";
        }

        $result = current(array_filter($class::$map, fn($item) => in_array($value, $item['possible_ids'])));

        if (is_array($result)) {
            return $return_as_array && !is_array($result['value'])
                ? [$result['value']]
                : $result['value'];
        }
    }



    public static function map(Request $request, $company_name)
    {
        // dd($request->caretaker);
        try {
            if ($company_name === 'ostina') {
                Caretaker::$data['availability']['availableFromDate'] = !empty($request->caretaker['UF_CRM_1632494109103']) ? date('Y-m-d', strtotime($request->caretaker['UF_CRM_1632494109103'])) : "";
                Caretaker::$data['availability']['availableUntilDate'] = !empty($request->caretaker['UF_CRM_1632469302326']) ? date('Y-m-d', strtotime($request->caretaker['UF_CRM_1632469302326'])) : "";
                Caretaker::$data['availability']['usualAssignmentDurationInWeeks'] = (int)$request->caretaker['UF_CRM_1548166699'];

                Caretaker::$data['personality']['characterTraits'] = self::set_from_array(CharacterTraitsMap::class, $request->caretaker['UF_CRM_1749562363201']);
                Caretaker::$data['personality']['freeTimeActivities']['hobbies'] = self::set_from_array(HobbyMap::class, $request->caretaker['UF_CRM_1742215459094']);
                Caretaker::$data['personality']['freeTimeActivities']['furtherHobbies'] = $request->caretaker['UF_CRM_1742460167860'];

                Caretaker::$data['personality']['smokingHabits']['isSmoker'] = self::set_value(TrueFalseMap::class, (int)$request->caretaker['UF_CRM_1542749591749']);
                Caretaker::$data['personality']['smokingHabits']['cigarettesPerDay'] = (int)$request->caretaker['UF_CRM_1685539662834'];
                Caretaker::$data['personality']['smokingHabits']['smokingOutsideOk'] = self::set_value(TrueFalseMap::class, (($v = data_get($request->caretaker, 'UF_CRM_1742218300014.0')) !== null ? (int)$v : ""));

                Caretaker::$data['personality']['motivation'] = $request->caretaker['UF_CRM_1742218437713'];

                Caretaker::$data['capabilitiesAndLimitations']['languageProficiencyGerman'] = self::set_value(LanguageMap::class, $request->caretaker['UF_CRM_1632469739901']);
                Caretaker::$data['capabilitiesAndLimitations']['driversLicense'] = self::set_value(TrueFalseMap::class, $request->caretaker['UF_CRM_1542748597387']);
                Caretaker::$data['capabilitiesAndLimitations']['health']['noLifting'] = self::set_value(TrueFalseMap::class, $request->caretaker['UF_CRM_1548164171']);

                Caretaker::$data['experience']['occupation'] = $request->caretaker['UF_CRM_1712605965814'];
                Caretaker::$data['experience']['schoolEducation'] = self::set_value(SchoolEducationMap::class, $request->caretaker['UF_CRM_1749562596724']);
                Caretaker::$data['experience']['careWorkExperienceInGermany'] = (int) preg_replace('/\D/', '', $request->caretaker['UF_CRM_1712607340580']);
                Caretaker::$data['experience']['careWorkExperienceOutsideOfGermany'] = (int) preg_replace('/\D/', '', $request->caretaker['UF_CRM_1712607377235']);

                Caretaker::$data['administrative']['agencyId'] = 293;
                Caretaker::$data['administrative']['employmentType'] = self::set_value(EmploymentTypeMap::class, $request->caretaker['UF_CRM_1742460370209']);

                Caretaker::$data['missionRequirements']['location']['neighborhood'] = self::set_value(NeighborhoodMap::class, $request->caretaker['UF_CRM_1749669132179']);
                Caretaker::$data['missionRequirements']['unlimitedInternetAccess'] = self::set_value(TrueFalseMap::class, $request->caretaker['UF_CRM_1742219814687']);
                Caretaker::$data['missionRequirements']['maxNumberPatients'] = self::set_value(MaxPatientsNumberMap::class, $request->caretaker['UF_CRM_1541757075974']);
                Caretaker::$data['missionRequirements']['gendersAccepted'] = self::set_value(GenderMap::class, $request->caretaker['UF_CRM_1742220053739'], true);
                Caretaker::$data['missionRequirements']['diseasesAccepted'] = self::set_from_array(AcceptedIllnessMap::class, $request->caretaker['UF_CRM_1742288863561']);
                Caretaker::$data['missionRequirements']['mobilityAccepted'] = self::set_from_array(AcceptedMobilityMap::class, $request->caretaker['UF_CRM_1626690363529']);
                Caretaker::$data['missionRequirements']['activities'] = self::set_from_array(ActivityMap::class, $request->caretaker['UF_CRM_1626690575338']);
                Caretaker::$data['missionRequirements']['relativesCloseByOk'] = self::set_value(RelativesCloseByMap::class, $request->caretaker['UF_CRM_1712606608105']);
                Caretaker::$data['missionRequirements']['additionalPersonsInHouseholdOk'] = self::set_value(YesNoMap::class, $request->caretaker['UF_CRM_1548168143']);
                Caretaker::$data['missionRequirements']['nightshiftOk'] = self::set_value(YesNoMap::class, $request->caretaker['UF_CRM_1548168143']);
                Caretaker::$data['missionRequirements']['petsOk'] = self::set_value(YesNoMap::class, $request->caretaker['UF_CRM_1548164252']);
                Caretaker::$data['missionRequirements']['minimumMonthlyIncome'] = (int) preg_replace('/\D/', '', $request->caretaker['UF_CRM_1548164480']);

                Caretaker::$data['identity']['firstName'] = $request->caretaker['NAME'];
                Caretaker::$data['identity']['lastName'] = $request->caretaker['LAST_NAME'];
                Caretaker::$data['identity']['gender'] = self::set_value(GenderMap::class, (int)$request->caretaker['UF_CRM_1554821237591']);
                Caretaker::$data['identity']['birthDate'] = !empty($request->caretaker['UF_CRM_1666093396404']) ? date('Y-m-d', strtotime($request->caretaker['UF_CRM_1666093396404'])) : "";
                Caretaker::$data['identity']['contactInformation']['email'] = (isset($request->caretaker['EMAIL']) && $request->caretaker['EMAIL'][0]) ? $request->caretaker['EMAIL'][0]['VALUE'] : null;
                Caretaker::$data['identity']['contactInformation']['mobilePhone'] = ($request->caretaker['PHONE'] && $request->caretaker['PHONE'][0]) ? $request->caretaker['PHONE'][0]['VALUE'] : null;
                Caretaker::$data['identity']['maritalStatus'] = self::set_value(MaritalStatusMap::class, (int)$request->caretaker['UF_CRM_1742213573168']) ?? "";
                Caretaker::$data['identity']['nationSpecific']['poland']['region'] = self::set_value(VoivodeshipMap::class, (int)$request->caretaker['UF_CRM_1742213288706']) ?? "";
                Caretaker::$data['identity']['nationSpecific']['poland']['pesel'] = $request->caretaker['UF_CRM_1742212208849'] ?? "";
                Caretaker::$data['identity']['biometricData']['weight'] = $request->caretaker['UF_CRM_1661853955423'] ?? "";
                Caretaker::$data['identity']['biometricData']['height'] = $request->caretaker['UF_CRM_1661853971949'] ?? "";
                Caretaker::$data['identity']['homeAddress']['zip'] = $request->caretaker['UF_CRM_1661259185870'] ?? "";
                Caretaker::$data['identity']['homeAddress']['city'] = $request->caretaker['UF_CRM_1742213969145'] ?? "";

                // if (Caretaker::$data['identity']['contactInformation']['email'] == null) {
                //     Error::add('email', 'Email opiekunki jest wymagany.');
                // }

                if (Caretaker::$data['identity']['contactInformation']['mobilePhone'] == null) {
                    Error::add('phone', 'Nr telefonu opiekunki jest wymagany.');
                }

                if ($request->caretaker['UF_CRM_1750056725195']) {
                    Caretaker::$data['id'] = $request->caretaker['UF_CRM_1750056725195'];
                }
            }
        } catch (\Throwable $th) {
            // throw new Exception($th->getMessage());
            return response()->json([
                'success' => false,
                'error' => [
                    'msg' => $th->getMessage(),
                    'file' => $th->getFile(),
                    'line' => $th->getLine()
                ]
            ]);
        }

        return Caretaker::$data;
    }
}
