<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Contains messages for invalid validation process to <code>App\Entity\Ad</code>.
 *
 * <pre>
 *          use App\Validator as AdsValidator;
 *          ...
 *          #[AdsValidator\AdFields]
 *          class Foo {...}
 * </pre>
 *
 * @see     AdFieldsValidator
 *
 * @since   0.0.1
 *
 * @author  WorHyako
 *
 * TODO: find way to rename this shit.
 */
#[\Attribute (\Attribute::TARGET_CLASS)]
class AdFields extends Constraint
{
    public string $incorrectPriceMessage = 'Invalid price value';

    public string $incorrectTextMessage = 'Invalid text value';

    public string $incorrectBannerMessage = 'Invalid banner value';

    public string $incorrectShowCountMessage = 'Invalid show count value';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
