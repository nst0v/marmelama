<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Разделы</x-slot>

        <style>
            .mm-admin-actions {
                display: grid;
                grid-template-columns: 1fr;
                gap: 12px;
                width: 100%;
            }

            @media (min-width: 48rem) {
                .mm-admin-actions {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }

            @media (min-width: 80rem) {
                .mm-admin-actions {
                    grid-template-columns: repeat(5, minmax(0, 1fr));
                }
            }

            .mm-admin-action {
                color: #1f2937;
            }

            .mm-admin-action-description {
                color: #6b7280;
            }

            .dark .mm-admin-action {
                color: #f8fafc;
            }

            .dark .mm-admin-action-description {
                color: #cbd5e1;
            }

            .mm-admin-action--cat {
                background: linear-gradient(135deg, #fff7ed, #ffedd5);
                border-color: #fed7aa;
            }

            .mm-admin-action--gallery {
                background: linear-gradient(135deg, #f0fdf4, #dcfce7);
                border-color: #bbf7d0;
            }

            .mm-admin-action--files {
                background: linear-gradient(135deg, #eff6ff, #dbeafe);
                border-color: #bfdbfe;
            }

            .mm-admin-action--breeding {
                background: linear-gradient(135deg, #faf5ff, #f3e8ff);
                border-color: #e9d5ff;
            }

            .mm-admin-action--reviews {
                background: linear-gradient(135deg, #fdf2f8, #fce7f3);
                border-color: #fbcfe8;
            }

            .dark .mm-admin-action--cat {
                background: rgba(251, 146, 60, 0.10);
                border-color: rgba(251, 146, 60, 0.28);
            }

            .dark .mm-admin-action--gallery {
                background: rgba(34, 197, 94, 0.10);
                border-color: rgba(34, 197, 94, 0.28);
            }

            .dark .mm-admin-action--files {
                background: rgba(59, 130, 246, 0.10);
                border-color: rgba(59, 130, 246, 0.28);
            }

            .dark .mm-admin-action--breeding {
                background: rgba(168, 85, 247, 0.10);
                border-color: rgba(168, 85, 247, 0.28);
            }

            .dark .mm-admin-action--reviews {
                background: rgba(236, 72, 153, 0.10);
                border-color: rgba(236, 72, 153, 0.28);
            }

            .mm-admin-action-icon--cat {
                background: #fb923c;
            }

            .mm-admin-action-icon--gallery {
                background: #22c55e;
            }

            .mm-admin-action-icon--files {
                background: #3b82f6;
            }

            .mm-admin-action-icon--breeding {
                background: #a855f7;
            }

            .mm-admin-action-icon--reviews {
                background: #ec4899;
            }

            .dark .mm-admin-action-icon--cat {
                background: rgba(251, 146, 60, 0.22);
                color: #fdba74;
            }

            .dark .mm-admin-action-icon--gallery {
                background: rgba(34, 197, 94, 0.22);
                color: #86efac;
            }

            .dark .mm-admin-action-icon--files {
                background: rgba(59, 130, 246, 0.22);
                color: #93c5fd;
            }

            .dark .mm-admin-action-icon--breeding {
                background: rgba(168, 85, 247, 0.22);
                color: #d8b4fe;
            }

            .dark .mm-admin-action-icon--reviews {
                background: rgba(236, 72, 153, 0.22);
                color: #f9a8d4;
            }
        </style>

        <div class="mm-admin-actions">
            @foreach($actions as $action)
                <a
                    href="{{ $action['url'] }}"
                    class="mm-admin-action mm-admin-action--{{ $action['theme'] }} group flex items-center shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                    style="height: 82px; display: flex; align-items: center; gap: 14px; padding: 0 18px; border-width: 1px; border-style: solid; border-radius: 18px;"
                >
                    <span
                        class="mm-admin-action-icon--{{ $action['theme'] }} flex shrink-0 items-center justify-center shadow-sm"
                        style="height: 44px; width: 44px; min-width: 44px; display: inline-flex; align-items: center; justify-content: center; color: #ffffff; line-height: 0; border-radius: 14px;"
                    >
                        @if(str_contains($action['icon'], '/'))
                            <span style="display: inline-flex; height: 24px; width: 24px; align-items: center; justify-content: center;">
                                {!! file_get_contents(public_path(ltrim($action['icon'], '/'))) !!}
                            </span>
                        @else
                            <x-dynamic-component :component="$action['icon']" style="display: block; height: 24px; width: 24px;" />
                        @endif
                    </span>

                    <span class="min-w-0" style="display: flex; min-width: 0; flex-direction: column; justify-content: center; gap: 5px;">
                        <span class="block truncate text-sm font-semibold leading-tight" style="display: block; font-size: 16px; line-height: 1.05;">
                            {{ $action['label'] }}
                        </span>
                        <span class="mm-admin-action-description mt-0.5 block truncate text-xs leading-tight" style="display: block; font-size: 13px; line-height: 1.1;">
                            {{ $action['description'] }}
                        </span>
                    </span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
