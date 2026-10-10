import { Monitor, Moon, Sun } from 'lucide-react';
import { useAppearance } from '@/hooks/use-appearance';
import type { Appearance } from '@/hooks/use-appearance';
import { cn } from '@/lib/utils';

export function AppearanceSelector() {
    const { appearance, updateAppearance } = useAppearance();

    const options: { value: Appearance; icon: React.ComponentType<{ className?: string }>; label: string }[] = [
        { value: 'light', icon: Sun, label: 'Light' },
        { value: 'dark', icon: Moon, label: 'Dark' },
        { value: 'system', icon: Monitor, label: 'System' },
    ];

    return (
        <div
            className={cn(
                'inline-flex items-center gap-1 rounded-lg bg-muted p-1',
            )}
            role="group"
            aria-label="Appearance mode"
        >
            {options.map(({ value, icon: Icon, label }) => (
                <button
                    key={value}
                    onClick={() => updateAppearance(value)}
                    className={cn(
                        'flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-sm font-medium transition-colors',
                        appearance === value
                            ? 'bg-card text-card-foreground shadow-sm'
                            : 'text-muted-foreground hover:bg-muted/50',
                    )}
                    aria-pressed={appearance === value}
                    aria-label={label}
                    title={label}
                >
                    <Icon className="h-4 w-4" />
                </button>
            ))}
        </div>
    );
}