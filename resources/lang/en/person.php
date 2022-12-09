<?php
return [
    "unavailable" => [
        "label" => "Status",
        "content" => "Not available"
    ],
    "live" => [
        "label" => "Live",
        "yes" => "Yes",
        "no" => "No"
    ],
    "gender" => [
        "label" => "Gender"
    ],
    "surname" => [
        "label" => "Surname",
        "null" => "?Surname?"
    ],
    "old_surname" => [
        "label" => "Old surname",
        "name" => [
            "label" => "Surname",
        ],
        "order" => [
            "label" => "serial number",
            "rule" => "non-repeating number greater than one",
            "help" => "serial numbers of change of surname"
        ]
    ],
    "name" => [
        "label" => "Name",
        "null" => "?Name?"
    ],
    "patronymic" => [
        "label" => "Patronymic",
        "null" => "?Patronymic?",
        "rule" => "in the absence of a middle name, put the symbol! (exclamation mark), leave the line blank if the middle name is unknown",
    ],
    "birth_date" => [
        "label" => "Date of Birth",
        "age" => "(:age)",
    ],
    "birth_place" => [
        "label" => "Place of Birth"
    ],
    "death_date" => [
        "label" => "Date of death",
        "interval" => [
            "short" => "(:death back)",
            "long" => "(:death back aged :age)"
        ],
    ],
    "burial_place" => [
        "label" => "Burial place"
    ],
    "note" => [
        "label" => "Note"
    ],
    "activities" => [
        "label" => "Activities"
    ],
    "emails" => [
        "label" => "Emails"
    ],
    "internet" => [
        "label" => "Internet",
        "name" => [
            "label" => "Name",
            "help" => "for example social network"
        ],
        "url" => [
            "label" => "url",
            "help" => "for example page social network"
        ]
    ],
    "phones" => [
        "label" => "Phones",
        "rule" => "numbers, without dash and plus symbol"
    ],
    "residences" => [
        "label" => "Residences",
        "name" => [
            "label" => "Address"
        ],
        "date" => [
            "label" => "Date of data validity",
            "content" => "(data is current on :date)"
        ]
    ],
    "parents" => [
        "label" => "Parents",
        "role" => [
            "label" => "Parent's role"
        ],
        "person" => [
            "label" => "Person"
        ]
    ],
    "marriages" => [
        "label" => "Marriage (cohabitation)",
        "role_current" => [
            "label" => "Role of the current person"
        ],
        "role_soulmate" => [
            "label" => "Partner role"
        ],
        "soulmate" => [
            "label" => "Partner name"
        ]
    ],
    "children" => [
        "label" => "Children"
    ],
    "brothers_sisters" => [
        "label" => "Brothers, sisters"
    ],
    "photo" => [
        "label" => "Photo",
        "date" => [
            "label" => "Date photo"
        ],
        "order" => [
            "label" => "Serial number",
            "rule" => "non-repeating number greater than one",
            "help" => "indicate the serial number of the photo"
        ],
        "adding" => "To add a photo, select a file",
        "missing" => "No photo"
    ],
    "date" => [
        "rule" => "leave the line blank if the date is unknown, otherwise the date must match the format yyyy-mm-dd, any unknown digit is replaced with a character? (question mark)"
    ],
    "crud" => [
        "show" => "Open a card",
        "create" => "Create",
        "store" => "Store",
        "edit" => "Edit",
        "update" => "Update",
        "destroy" => "Destroy",
        "close" => "Close",
        "list_input" => [
            "add" => "Add",
            "del" => "Delete"
        ],
        "message" => [
            "ok" => [
                "save" => "Person saved successfully",
                "store" => "Person created successfully",
                "update" => "Data updated successfully",
                "destroy" => "Person successfully removed",
            ],
            "error" => [
                "not_valid" => "Form fields filled in incorrectly ",
                "common" => "An error has occurred, try again, if the error persists contact the developer"
            ],
            "confirmation" => "Cancellation is not possible, continue?"
        ]
    ]
];
