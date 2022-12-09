<?php

return [
    "title" => "Upcoming events",
    "button" => [
        "title" => "Upcoming events"
    ],
    "past" => "Past",
    "today" => "Today",
    "nearest" => "Nearest",
    "not_found" => "No upcoming events",
    "birth" => [
        "name" => "Birthday",
        "will_be" => "will be",
        "fulfilled" => "fulfilled",
        "past" => "ago"
    ],
    "death" => [
        "name" => "Memorial Day",
        "passed" => ":interval back",
        "passed_age" => ":interval back at the age of :age"
    ],
    "subscription" => [
        "create" => [
            "title" => "Subscribe to upcoming events",
            "manual" => [
                "what" => "Here you can subscribe to receive upcoming events.",
                    "description" => "Subscription is only available for Telegram users! To create a subscription, you need to launch a telegram bot
                    by clicking on the link (link below) and send it the code located below. Wait for the subscription to be issued.
                    You can unsubscribe at any time on this site, or simply by blocking the telegram bot.",
                "call_action" => "To create a subscription, follow the steps (it is important to follow the order):",
                "step_1_1" => "Follow the link:",
                "step_1_2" => "Depending on the features of your software, you will be immediately redirected to a Telegram
                    or you will be asked to confirm the transition to an external resource, you may need to select a Telegram program
                    from the list. Follow these steps.",
                "step_1_3" => "If you failed to perform the previous action, you can manually open Telegram, and through \"search\" find
                    telegram bot with the name:",
                "step_2" => "Launch Telegram bot.",
                "step_3" => "Send him a message with the code:",
                "step_4" => "And finally complete the steps by subscribing. Click",
                "button" => [
                    "copy" => [
                        "tooltip" => "Copy to Clipboard"
                    ],
                    "register" => [
                        "label" => "Subscribe",
                        "tooltip" => "Subscribe"
                    ]
                ]
            ]
        ],
        "edit" => [
            "info" => "You are subscribed to receive notifications about upcoming events."
        ],
        "crud" => [
            "create" => [
                "tooltip" => "Subscribe to receive events"
            ],
            "delete" => [
                "label" => "Unsubscribe",
                "tooltip" => "Unsubscribe from receiving events",
                "confirmation" => "The subscription to receive upcoming events will be deleted. Continue?"
            ],
            "message" => [
                "ok" => [
                    "store" => "You have successfully subscribed to receive upcoming events.",
                    "delete" => "You have successfully unsubscribed."
                ],
                "error" => [
                    "store" => "The waiting time has expired! The response from the bot's telegrams has not been received. Try again, if the error persists, contact the site administrator."
                ]
            ],
        ]
    ]
];
