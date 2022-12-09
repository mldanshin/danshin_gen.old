<?php

namespace Tests\DataProvider;

use App\View\Tree\Size;
use App\View\Tree\StylePerson;

trait View
{
    private function getStylePerson(): StylePerson
    {
        return new StylePerson(
            config("app.tree.style.person_lg.margine"),
            config("app.tree.style.person_lg.stroke_width"),
            config("app.tree.style.person_lg.padding"),
            config("app.tree.style.person_lg.font_size"),
            config("app.tree.style.person_lg.line_spacing"),
            new Size(
                config("app.tree.style.person_lg.button_width"),
                config("app.tree.style.person_lg.button_height")
            )
        );
    }
}
