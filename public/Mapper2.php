<?php

class Mapper
{
    public $mapped = [];
    public $lines;

    // jest ALBO pattern
    // ALBO key
    public $control_array = [
        [
            'name' => 'start_date',
            'pattern' => '/^Beginn: (.*)/'
        ],
        // [
        //     'name' => 'budget',
        //     'pattern' => '/^Budget: (.*)/'
        // ],
        // [
        //     'name' => 'language',
        //     'pattern' => '/^Deutschkenntnisse: (.*)/'
        // ],
        // [
        //     'name' => 'carer_sex',
        //     'pattern' => '/^Geschlechtswunsch: (.*)/'
        // ],
        // [
        //     'name' => 'smoking',
        //     'pattern' => '/^Raucher: (.*)/'
        // ],
        [
            'name' => 'transfer',
            'pattern' => '/^Transfer: (.*)/'
        ],
        [
            'name' => 'waking_up',
            'pattern' => '/^Nachteinsätze: (.*)/'
        ],
        [
            'name' => 'internet',
            'pattern' => '/^Internet: (.*)/'
        ],
        [
            'name' => 'carer_room',
            'pattern' => '/^Wohnsituation BK: (.*)/'
        ],
        [
            'name' => 'bathroom',
            'pattern' => '/^Badezimmer: (.*)/'
        ],
        [
            'name' => 'formal_title',
            'key' => 'anrede'
        ],
        [
            'name' => 'first_name',
            'key' => 'name'
        ],
        [
            'name' => 'last_name',
            'key' => 'vorname'
        ],
        [
            'name' => 'street',
            'key' => 'straße',
            'check_next_line' => [
                'pattern' => '/^(\d+)([a-zA-Z]?)(-?\d*[a-zA-Z]?)?(\/\d+)?( bis)?$/'
            ]
        ],
        [
            'name' => 'zip_code_and_city',
            'key' => 'plz / ort',
            'split' => [
                'zip_code',
                'city'
            ]
        ],
        [
            'name' => 'phone_private',
            'key' => 'telefon'
        ],
        [
            'name' => 'phone_mobile',
            'key' => 'mobil',
            'not' => 'Mobilität'
        ],
        [
            'name' => 'email',
            'key' => 'e-mail'
        ],
        [
            'name' => 'relationship_degree',
            'key' => 'Verwandtschaftsgrad'
        ],
        [
            'name' => 'patient_first_name',
            'key' => 'name'
        ],
        [
            'name' => 'patient_last_name',
            'key' => 'vorname'
        ],
        [
            'name' => 'patient_gender',
            'key' => 'Geschlecht'
        ],
        [
            'name' => 'patient_weight',
            'key' => 'Gewicht'
        ],
        [
            'name' => 'patient_birth_date',
            'key' => 'Geburtsdatum'
        ],
        [
            'name' => 'patient_height',
            'key' => 'Größe'
        ],
        [
            'name' => 'marital_status',
            'key' => 'Familienstand'
        ],
        [
            'name' => 'additional_care',
            'key' => 'Weitere pflegebedürftige Mitbewohner'
        ],
        [
            'name' => 'patient_street',
            'key' => 'straße',
            'check_next_line' => [
                'pattern' => '/^(\d+)([a-zA-Z]?)(-?\d*[a-zA-Z]?)?(\/\d+)?( bis)?$/'
            ]
        ],
        [
            'name' => 'zip_code_and_city',
            'key' => 'plz / ort',
            'split' => [
                'patient_zip_code',
                'patient_city'
            ]
        ],
        [
            'name' => 'caretaker_before',
            'key' => 'Wurde die zu betreuende Person bereits durch eine Betreuungskraftt betreut?'
        ],
        [
            'name' => 'phone_private',
            'key' => 'telefon'
        ],
        [
            'name' => 'phone_mobile',
            'key' => 'mobil',
            'not' => 'Mobilität'
        ],
        [
            'name' => 'email',
            'key' => 'e-mail'
        ],
        [
            'name' => 'pflegegrad',
            'key' => 'Pflegegrad'
        ],
        [
            'name' => 'diseases',
            'key' => 'Diagnosen',
            'explode' => true
        ],
        [
            'name' => 'pflegedienst',
            'key' => 'Ist aktuell ein Pflegedienst beauftragt:'
        ],
        [
            'name' => 'pflegedienst_frequency',
            'key' => 'Falls Ja, wie oft kommen Pfleger pro Tag?'
        ],
        [
            'name' => 'pflegedienst_still_coming',
            'key' => 'Wird der Pflegedienst auch zukünftig genutzt?'
        ],
        [
            'name' => 'orientation_and_mobility',
            'key' => 'Orientierung & Mobilität'
        ],
        [
            'name' => 'personal_orientation',
            'key' => 'Orientierung persönlich'
        ],
        [
            'name' => 'local_orientation',
            'key' => 'Orientierung örtlich'
        ],
        [
            'name' => 'time_orientation',
            'key' => 'Orientierung zeitlich'
        ],
        [
            'name' => 'mobility',
            'key' => 'Mobilität generell',
            'explode' => true
        ],
        [
            'name' => 'patient_lifting',
            'key' => 'Muss die zu betreuende Person gehoben werden?'
        ],
        [
            // czy pacjent musi byc leżakowany lub przekręcany w łóżku?
            'name' => 'patient_bedtime_help',
            'key' => 'Muss die zu betreuende Person im Bett gelagert oder gedreht werden?'
        ],
        [
            'name' => 'stairs',
            'key' => 'Treppensteigen?'
        ],
        [
            'name' => 'aids',
            'key' => 'Vorhandene Hilfsmittel:',
            'explode' => true,
            'can_exists' => true
        ],
        [
            'name' => 'additional_aids',
            'key' => 'Andere Hilfsmittel:'
        ],
        [
            'name' => 'urine_control',
            'key' => 'Urinkontrolle'
        ],
        [
            'name' => 'aids',
            'key' => 'Hilfsmittel',
            'explode' => true,
            'can_exists' => true
        ],
        [
            'name' => 'shit_happens',
            'key' => 'Stuhlkontrolle'
        ],
        [
            'name' => 'dressing_and_undressing',
            'key' => 'An-/Auskleiden'
        ],
        [
            'name' => 'help_bathroom',
            'key' => 'Baden/Duschen und Körperpflege'
        ],
        [
            'name' => 'help_face_care',
            'key' => 'Gesicht'
        ],
        [
            'name' => 'help_teeth',
            'key' => 'Mundpflege'
        ],
        [
            'name' => 'help_with_upper_body_parts',
            'key' => 'Oberkörperpflege'
        ],
        [
            'name' => 'help_with_ass_and_legs',
            'key' => 'Gesäß/Beine Pflege'
        ],
        [
            'name' => 'help_intimate_care',
            'key' => 'Intimpflege'
        ],
        [
            'name' => 'help_washing_hair',
            'key' => 'Haare waschen'
        ],
        [
            'name' => 'help_shaving',
            'key' => 'Rasieren'
        ],
        [
            'name' => 'help_hand_care',
            'key' => 'Handpflege'
        ],
        [
            'name' => 'help_foot_care',
            'key' => 'Fußpflege'
        ],
        [
            'name' => 'help_eating_and_drinking',
            'key' => 'Essen / Trinken'
        ],
        [
            'name' => 'chewing_and_swallowing_disorders',
            'key' => 'Kau- & Schluckstörungen',
            'explode' => true
        ],
        [
            'name' => 'diet_and_meal_plan',
            'key' => 'Diät bzw. Speiseplan'
        ],
        [
            'name' => 'sleeping',
            'key' => 'Einschlafen & Durchschlafen'
        ],
        [
            'name' => 'night_jobs',
            'key' => 'Nachteinsätze'
        ],
        [
            'name' => 'night_jobs_description',
            'key' => 'Informationen'
        ],
        [
            'name' => 'carer_sex',
            'key' => 'Profil des Personals'
        ],
        [
            'name' => 'language',
            'key' => 'Sprachkenntnisse'
        ],
        [
            'name' => 'budget',
            'key' => 'Budget',
            'not' => 'Budget:'
        ],
        [
            'name' => 'ort',
            'key' => 'Ort'
        ],
        [
            'name' => 'shopping_in_town',
            'key' => 'Einkaufsmöglichkeiten vor Ort'
        ],
        [
            'name' => 'carer_place_to_stay',
            'key' => 'Wohnsituation der Betreuungskraft'
        ],
        [
            'name' => 'carer_items_for_help',
            'key' => 'Ausstattung für die Betreuungskraft',
            'explode' => true
        ],
        [
            'name' => 'smoking',
            'key' => 'Rauchen gestattet? gestattet'
        ],
        [
            'name' => 'pets',
            'key' => 'Haustiere'
        ],
        [
            'name' => 'shopping_person',
            'key' => 'Einkäufe übernimmt...'
        ],
        [
            'name' => 'cooking',
            'key' => 'Kochen / Essensvorbereitung'
        ],
        [
            'name' => 'washing_and_ironing',
            'key' => 'Waschen / Bügeln'
        ],
        [
            'name' => 'assist_with_doctor_visits',
            'key' => 'Begleitung bei Arzt- & Apothekenbesuchen'
        ],
        [
            'name' => 'house_help',
            'key' => 'Gibt es eine Haushaltshilfe?'
        ],
        [
            'name' => 'morning_plan',
            'key' => 'Tagesstruktur Morgens',
        ],
        [
            'name' => 'forenoon_plan',
            'key' => 'Tagesstruktur Vormittags',
        ],
        [
            'name' => 'noon_plan',
            'key' => 'Tagesstruktur Mittags',
        ],
        [
            'name' => 'afternoon_plan',
            'key' => 'Tagesstruktur Nachmittags',
        ],
        [
            'name' => 'evening_plan',
            'key' => 'Tagesstruktur Abends',
        ],
        [
            'name' => 'night_plan',
            'key' => 'Tagesstruktur Nachts',
        ],
    ];

    public function __construct($lines)
    {
        $this->lines = $lines;
    }

    public function map()
    {
        foreach ($this->lines as $line_number => $line) {
            if ($line == '') {
                continue;
            }

            $line = trim($line);
            $current_mapped_count = count($this->mapped);
            $force_key_delete = false;

            foreach ($this->control_array as $control_index => $control) {
                if (isset($control['pattern'])) {
                    $this->map_by_regex($control, $line);
                    break;
                } else if (isset($control['key'])) {
                    if ($this->compare($line, $control)) {
                        if (isset($control['split'])) {
                            $this->map_by_split($control['split'], $line_number);
                            break;
                        } else if (isset($control['check_next_line'])) {
                            $this->map_with_next_line($control, $line_number);
                            break;
                        } else if (isset($control['explode'])) {
                            $arr = $this->extract($line_number + 1);

                            if (isset($control['can_exists']) and !empty(array_filter($this->mapped, fn ($item) => $item['name'] == $control['name']))) {
                                $searched_index = $this->find_index($control['name']);
                                $arr = array_merge(explode(', ', $this->mapped[$searched_index]['value']), $arr);
                                $this->map_by_replace($searched_index, implode(', ', $arr));
                                $force_key_delete = true;
                            } else {
                                $this->map_by_value($control['name'], implode(', ', $arr));
                            }

                            break;
                        } else {
                            $this->map_by_name($control['name'], ($line_number + 1));
                            break;
                        }
                    }
                }
            }

            if (count($this->mapped) > $current_mapped_count or $force_key_delete) {
                unset($this->control_array[$control_index]);
            }
        }
    }

    public function map_by_replace($index, $value)
    {
        $this->mapped[$index]['value'] = $value;
        return true;
    }

    public function find_index($name)
    {
        foreach ($this->mapped as $index => $item) {
            if ($item['name'] == $name) {
                return $index;
            }
        }

        return null;
    }

    public function extract($line_number)
    {
        $arr = explode(',', $this->lines[$line_number]);
        $arr = array_map('trim', $arr);

        return $arr;
    }

    public function map_with_next_line($control, $line_number)
    {
        $next_line = trim($this->lines[$line_number + 2]);

        if (preg_match($control['check_next_line']['pattern'], $next_line)) {
            $value = implode(' ', [$this->lines[$line_number + 1], $next_line]);
            $this->map_by_value($control['name'], $value);
        } else {
            $this->map_by_name($control['name'], ($line_number + 1));
        }

        return true;
    }

    public function map_by_split($split_arr, $line_number)
    {
        foreach ($split_arr as $split_index => $split) {
            $this->map_by_name($split, ($line_number + 1 + $split_index));
        }

        return true;
    }

    public function compare($line, $control)
    {
        return strpos(strtolower($line), strtolower($control['key'])) === 0 and (!isset($control['not']) or strpos(strtolower($line), strtolower($control['not'])) === false);
    }

    public function map_by_value($name, $value)
    {
        $value = trim($value);

        $this->mapped[] = [
            'name' => $name,
            'value' => $value,
        ];

        return true;
    }

    public function map_by_name($name, $index)
    {
        $this->map_by_value($name, $this->lines[$index]);
        return true;
    }

    public function map_by_regex($control, $line)
    {
        if (preg_match($control['pattern'], $line)) {
            $value = explode(':', $line)[1];

            if ($control['name'] == 'start_date') {
                $value = date('Y-m-d', strtotime($value));
            }

            $this->map_by_value($control['name'], $value);
            return true;
        }

        return false;
    }
}
