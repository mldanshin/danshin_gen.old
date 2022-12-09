<?php

namespace App\Http\Requests\Person;

use App\Models\Eloquent\Gender as GenderEloquentModel;
use App\Models\Eloquent\Marriage as MarriageEloquentModel;
use App\Models\Eloquent\MarriageRoleScope as MarriageRoleScopeEloquentModel;
use App\Models\Eloquent\People as PeopleEloquentModel;
use App\Models\Eloquent\ParentChild as ParentChildEloquentModel;
use App\Models\Person\Editable\OldSurname as OldSurnameModel;
use App\Models\Person\Editable\Internet as InternetModel;
use App\Models\Person\Editable\Photo as PhotoModel;
use App\Models\Person\Editable\Residence as ResidenceModel;
use App\Models\Person\Editable\Request\Marriage as MarriageModel;
use App\Models\Person\Editable\Request\ParentModel as ParentModel;
use App\Models\Person\Editable\Request\Person as PersonModel;
use App\Repositories\Person\PhotoFileSystem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

final class EditableRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array|mixed[]
     */
    public function rules()
    {
        $patternDate = "regex:/[0-9\?]{4}-([0\?]{1}[1-9\?]{1}|[1\?]{1}[012\?]{1})-([0-2\?]{1}[0-9\?]{1}|[3\?]{1}[01\?]{1})/";
        $personId = PeopleEloquentModel::where("id", $this->person_id)->value("id");

        return [
            "person_id" => "bail|required|integer",
            "person_unavailable" => "sometimes|accepted",
            "person_live" => "sometimes|accepted",
            "person_gender" => "bail|required|integer|exists:genders,id",
            "person_surname" => "nullable|string",
            "person_old_surnames" => "sometimes|array",
            "person_old_surnames.*.name" => "required_with:person_old_surnames|string",
            "person_old_surnames.*.order" => "required_with:person_old_surnames|integer|min:1|distinct",
            "person_name" => "nullable|string",
            "person_patronymic" => "nullable|string",
            "person_birth_date" => [
                "nullable",
                $patternDate
            ],
            "person_birth_place" => "nullable|string",
            "person_death_date" => [
                "nullable",
                $patternDate
            ],
            "person_burial_place" => "nullable|string",
            "person_note" => "nullable|string",
            "person_activities" => "sometimes|array",
            "person_activities.*" => "required_with:person_activities|string|distinct",
            "person_emails" => "sometimes|array",
            "person_emails.*" => [
                "required_with:person_emails",
                "email",
                "distinct",
                Rule::unique('emails', "name")->ignore($personId, "person_id")
            ],
            "person_internet" => "sometimes|array",
            "person_internet.*.name" => "required_with:person_internet|string",
            "person_internet.*.url" => "required_with:person_internet|url|distinct",
            "person_phones" => "sometimes|array",
            "person_phones.*" => [
                "required_with:person_phones",
                "string",
                "distinct",
                "regex:/^\d+$/",
                Rule::unique('phones', "name")->ignore($personId, "person_id")
            ],
            "person_residences" => "sometimes|array",
            "person_residences.*.name" => "required_with:person_residences|string|distinct",
            "person_residences.*.date" => [
                "nullable",
                "distinct",
                $patternDate
            ],
            "person_parents" => "sometimes|array",
            "person_parents.*.person" => [
                "required_with:person_parents",
                "integer",
                "distinct",
                "exists:people,id",
                "different:person_id",
                "different:person_marriages.*.soulmate",
            ],
            "person_parents.*.role" => "required_with:person_parents|integer|exists:parent_roles,id",
            "person_marriages" => "sometimes|array",
            "person_marriages.*.role_current" => [
                "required_with:person_marriages",
                "integer",
                "exists:marriage_roles,id",
            ],
            "person_marriages.*.soulmate" => [
                "required_with:person_marriages",
                "integer",
                "distinct",
                "exists:people,id",
                "different:person_id",
                "different:person_parents.*.person",
            ],
            "person_marriages.*.role_soulmate" => "required_with:person_marriages|integer|exists:marriage_roles,id",
            "person_photo" => "sometimes|array",
            "person_photo.*.url" => "required_with:person_photo|distinct|string",
            "person_photo.*.path_relative" => "required_with:person_photo|distinct|string",
            "person_photo.*.date" => [
                "nullable",
                $patternDate
            ],
            "person_photo.*.order" => "required_with:person_photo|integer|min:1|distinct",
        ];
    }

    public function getPerson(): PersonModel
    {
        return new PersonModel(
            $this->person_id,
            $this->boolean("person_unavailable"),
            $this->boolean("person_live"),
            $this->person_gender,
            $this->input("person_surname"),
            $this->getItem(
                "person_old_surnames",
                fn($item) => new OldSurnameModel($item["name"], $item["order"])
            ),
            $this->input("person_name"),
            $this->convertPatronymic($this->input("person_patronymic")),
            $this->input("person_birth_date"),
            $this->input("person_birth_place"),
            $this->input("person_death_date"),
            $this->input("person_burial_place"),
            $this->input("person_note"),
            $this->has("person_activities") ? collect($this->input("person_activities")) : null,
            $this->has("person_emails") ? collect($this->input("person_emails")) : null,
            $this->getItem(
                "person_internet",
                fn($item) => new InternetModel($item["url"], $item["name"])
            ),
            $this->has("person_phones") ? collect($this->input("person_phones")) : null,
            $this->getItem(
                "person_residences",
                fn($item) => new ResidenceModel($item["name"], $item["date"])
            ),
            $this->getItem(
                "person_parents",
                fn($item) => new ParentModel($item["person"], $item["role"])
            ),
            $this->getItem(
                "person_marriages",
                fn($item) => new MarriageModel($item["role_current"], $item["soulmate"], $item["role_soulmate"])
            ),
            $this->getItem(
                "person_photo",
                fn($item) => new PhotoModel($item["url"], $item["path_relative"], $item["date"], $item["order"])
            ),
        );
    }

    public function withValidator(Validator $validator): void
    {
        if (!empty($this->person_id)) {
            $this->redirect = route("partials.person.edit", [$this->person_id]);
        }

        $this->validateDate($validator);
        $this->validateParentPossible($validator);
        $this->validateParentRoleGender($validator);
        $this->validateMarriage($validator);
        $this->validateDifferentParentAndSoulmate($validator);
        $this->validatePhotoPath($validator);
    }

    private function convertPatronymic(?string $dirty): ?string
    {
        if ($dirty === "!") {
            return null;
        } elseif (empty($dirty)) {
            return "";
        } else {
            return $dirty;
        }
    }

    /**
     * @return Collection|mixed[]|null
     */
    private function getItem(string $name, callable $func): ?Collection
    {
        if ($this->has($name)) {
            $array = [];
            foreach ($this->$name as $item) {
                $array[] = $func($item);
            }
            return collect($array);
        } else {
            return null;
        }
    }

    private function validateDate(Validator $validator): void
    {
        $current = date("Y-m-d");
        $birth = $this->person_birth_date;
        $death = $this->person_death_date;

        $this->validateCompareDate(
            $birth,
            $current,
            $validator,
            "person_birth_date",
            __("validation.date_birth.after_current")
        );
        $this->validateCompareDate(
            $death,
            $current,
            $validator,
            "person_death_date",
            __("validation.date_death.after_current")
        );
        $this->validateCompareDate(
            $birth,
            $death,
            $validator,
            "person_death_date",
            __("validation.date_birth.after_death")
        );

        if ($this->person_residences !== null) {
            $personResidences = array_map(
                function ($item) {
                    if (isset($item["date"])) {
                        return $item["date"];
                    }
                },
                $this->person_residences
            );
            foreach ($personResidences as $item) {
                $this->validateCompareDate(
                    $birth,
                    $item,
                    $validator,
                    "person_residences",
                    __(
                        "validation.date_between.before_birth",
                        ["field" => __("validation.attributes.person_residences")]
                    )
                );
                $this->validateCompareDate(
                    $item,
                    $death,
                    $validator,
                    "person_residences",
                    __(
                        "validation.date_between.after_death",
                        ["field" => __("validation.attributes.person_residences")]
                    )
                );
                $this->validateCompareDate(
                    $item,
                    $current,
                    $validator,
                    "person_residences",
                    __(
                        "validation.date_between.after_current",
                        ["field" => __("validation.attributes.person_residences")]
                    )
                );
            }
        }

        if ($this->person_photo !== null) {
            $personPhoto = array_map(
                function ($item) {
                    if (isset($item["date"])) {
                        return $item["date"];
                    }
                },
                $this->person_photo
            );
            foreach ($personPhoto as $item) {
                $this->validateCompareDate(
                    $birth,
                    $item,
                    $validator,
                    "person_photo",
                    __(
                        "validation.date_between.before_birth",
                        ["field" => __("validation.attributes.person_photo")]
                    )
                );
                $this->validateCompareDate(
                    $item,
                    $death,
                    $validator,
                    "person_photo",
                    __(
                        "validation.date_between.after_death",
                        ["field" => __("validation.attributes.person_photo")]
                    )
                );
                $this->validateCompareDate(
                    $item,
                    $current,
                    $validator,
                    "person_photo",
                    __(
                        "validation.date_between.after_current",
                        ["field" => __("validation.attributes.person_photo")]
                    )
                );
            }
        }
    }

    private function validateCompareDate(
        ?string $dateBefore,
        ?string $dateAfter,
        Validator $validator,
        string $fieldTarget,
        string $messageError
    ): void {

        if ($dateBefore === null || $dateAfter === null) {
            return;
        }

        for ($i = 0; $i <= 9; $i++) {
            if ($dateBefore[$i] === "-" || $dateAfter[$i] === "-") {
                continue;
            }

            if ($dateBefore[$i] === "?" || $dateAfter[$i] === "?") {
                break;
            } elseif ($dateBefore[$i] < $dateAfter[$i]) {
                break;
            } elseif ($dateBefore[$i] > $dateAfter[$i]) {
                $validator->after(function ($validator) use ($fieldTarget, $messageError) {
                    $validator->errors()->add($fieldTarget, $messageError);
                });
                break;
            } else {
                continue;
            }
        }
    }

    private function validateParentRoleGender(Validator $validator): void
    {
        if ($this->person_parents !== null) {
            foreach ($this->person_parents as $parent) {
                if (!empty($parent["person"]) && !empty($parent["role"])) {
                    $rolesId = PeopleEloquentModel::find($parent["person"])
                        ?->gender()
                        ->first()
                        ->parents()
                        ->pluck("parent_id")
                        ->all();
                    if ($rolesId !== null && !in_array($parent["role"], $rolesId)) {
                        $validator->after(function ($validator) {
                            $validator->errors()->add(
                                "person_parents",
                                __("validation.parent_role_gender")
                            );
                        });
                    }
                }
            }
        }
    }

    private function validateParentPossible(Validator $validator): void
    {
        if ($this->person_parents !== null) {
            foreach ($this->person_parents as $parent) {
                $chldrensId = ParentChildEloquentModel::where("parent_id", $this->person_id)->pluck("child_id")->all();
                if (in_array($chldrensId, $parent)) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add(
                            "person_parents",
                            __("validation.parent_children")
                        );
                    });
                }

                $mariages1Id = MarriageEloquentModel::where("person1_id", $this->person_id)->pluck("person2_id")->all();
                $mariages2Id = MarriageEloquentModel::where("person2_id", $this->person_id)->pluck("person1_id")->all();
                $mariagesId = array_merge($mariages1Id, $mariages2Id);
                if (in_array($mariagesId, $parent)) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add(
                            "person_parents",
                            __("validation.parent_marriage")
                        );
                    });
                }
            }
        }
    }

    private function validateMarriage(Validator $validator): void
    {
        if ($this->person_marriages !== null) {
            foreach ($this->person_marriages as $marriage) {
                $this->validateMarriageSoulmateRoleGender($validator, $marriage);
                $this->validateMarriageRoleScope($validator, $marriage);
                $this->validateMarriageCurrentRoleGender($validator, $marriage);
            }
        }
    }

    /**
     * @param array|string[] $marriage
     */
    private function validateMarriageCurrentRoleGender(Validator $validator, array $marriage): void
    {
        if (!empty($marriage["role_current"]) && $this->person_gender !== null) {
            $rolesId = GenderEloquentModel::find($this->person_gender)?->marriages()->pluck("role_id")->all();
            if ($rolesId !== null && !in_array($marriage["role_current"], $rolesId)) {
                $validator->after(function ($validator) {
                    $validator->errors()->add(
                        "person_marriages",
                        __("validation.marriage_current_role_gender")
                    );
                });
            }
        }
    }

    /**
     * @param array|string[] $marriage
     */
    private function validateMarriageSoulmateRoleGender(Validator $validator, array $marriage): void
    {
        if (!empty($marriage["soulmate"]) && !empty($marriage["role_soulmate"])) {
            $rolesId = PeopleEloquentModel::find($marriage["soulmate"])
                ?->gender()
                ->first()
                ->marriages()
                ->pluck("role_id")
                ->all();
            if ($rolesId !== null && !in_array($marriage["role_soulmate"], $rolesId)) {
                $validator->after(function ($validator) {
                    $validator->errors()->add(
                        'person_marriages',
                        __("validation.marriage_soulmate_role_gender")
                    );
                });
            }
        }
    }

    /**
     * @param array|string[] $marriage
     */
    private function validateMarriageRoleScope(Validator $validator, array $marriage): void
    {
        if (!empty($marriage["role_current"]) && !empty($marriage["role_soulmate"])) {
            $id = MarriageRoleScopeEloquentModel::where([
                "role1_id" => $marriage["role_current"],
                "role2_id" => $marriage["role_soulmate"]
                ])
                ->value("id");
            if ($id === null) {
                $validator->after(function ($validator) {
                    $validator->errors()->add(
                        "person_marriages",
                        __("validation.marriage_role_scope")
                    );
                });
            }
        }
    }

    private function validateDifferentParentAndSoulmate(Validator $validator): void
    {
        if ($this->person_parents !== null && $this->person_marriages !== null) {
            $parents = array_map(
                fn($item) => $item["person"],
                $this->person_parents
            );
            $marriages = array_map(
                fn($item) => $item["soulmate"],
                $this->person_marriages
            );
            foreach ($parents as $parent) {
                if (in_array($parent, $marriages)) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add(
                            "person_marriages",
                            __("validation.different_parent_and_soulmate")
                        );
                    });
                }
            }
        }
    }

    private function validatePhotoPath(Validator $validator): void
    {
        if ($this->person_photo !== null) {
            foreach ($this->person_photo as $item) {
                $fileSystem = PhotoFileSystem::instance();
                if (!empty($item["path_relative"]) && $fileSystem->getDisk()->exists($item["path_relative"]) === false) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add(
                            'person_photo',
                            __("validation.photo_url_unknown")
                        );
                    });
                }
            }
        }
    }
}
