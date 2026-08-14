<x-filament-widgets::widget>
    <style>
        .mm-admin-welcome {
            position: relative;
            overflow: hidden;
            border: 1px solid #ead7c4;
            border-radius: 24px;
            background:
                radial-gradient(circle at 92% 18%, rgba(139, 94, 60, 0.16), transparent 30%),
                linear-gradient(135deg, #fffaf5 0%, #fff4e8 48%, #ffffff 100%);
            box-shadow: 0 14px 34px rgba(139, 94, 60, 0.10);
        }

        .mm-admin-welcome::after {
            content: "";
            position: absolute;
            right: -28px;
            bottom: -40px;
            width: 150px;
            height: 150px;
            border-radius: 999px;
            background: rgba(139, 94, 60, 0.08);
        }

        .mm-admin-welcome-inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 18px;
        }

        .mm-admin-welcome-mark {
            display: inline-flex;
            width: 46px;
            height: 46px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            border: 1px solid #ead7c4;
            background: #faf7f2;
            color: #8b5e3c;
            box-shadow: 0 12px 24px rgba(139, 94, 60, 0.16);
        }

        .mm-admin-welcome-mark svg {
            display: block;
            width: 34px;
            height: 34px;
        }

        .mm-admin-welcome-eyebrow {
            color: #8b5e3c;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            line-height: 1;
            text-transform: uppercase;
        }

        .mm-admin-welcome-title {
            margin-top: 6px;
            color: #24170f;
            font-size: 22px;
            font-weight: 750;
            letter-spacing: -0.03em;
            line-height: 1.08;
        }

        .mm-admin-welcome-text {
            margin-top: 6px;
            max-width: 620px;
            color: #765f4b;
            font-size: 14px;
            line-height: 1.35;
        }

        .dark .mm-admin-welcome {
            border-color: rgba(251, 191, 36, 0.15);
            background:
                radial-gradient(circle at 90% 12%, rgba(251, 191, 36, 0.12), transparent 32%),
                linear-gradient(135deg, rgba(69, 42, 24, 0.52), rgba(31, 25, 22, 0.92));
            box-shadow: none;
        }

        .dark .mm-admin-welcome::after {
            background: rgba(251, 191, 36, 0.07);
        }

        .dark .mm-admin-welcome-mark {
            border-color: rgba(251, 191, 36, 0.20);
            background: #fff7ed;
            color: #8b5e3c;
            box-shadow: none;
        }

        .dark .mm-admin-welcome-eyebrow {
            color: #fcd34d;
        }

        .dark .mm-admin-welcome-title {
            color: #fff7ed;
        }

        .dark .mm-admin-welcome-text {
            color: #e7d7c7;
        }
    </style>

    <div class="mm-admin-welcome">
        <div class="mm-admin-welcome-inner">
            <div class="mm-admin-welcome-mark" aria-hidden="true">
                {!! file_get_contents(public_path('admin-icons/cat-paw.svg')) !!}
            </div>

            <div>
                <div class="mm-admin-welcome-eyebrow">МарМелАма</div>
                <h2 class="mm-admin-welcome-title">
                    {{ filled($name) ? $name.', ' : '' }}всё под рукой
                </h2>
                <p class="mm-admin-welcome-text">
                    Котята, помёты, слайды и отзывы собраны ниже в быстрых действиях.
                </p>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
