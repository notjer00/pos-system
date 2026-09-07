import { Link } from '@inertiajs/react';
import AppLogoIcon from '@/components/app-logo-icon';
import { home } from '@/routes';
import type { AuthLayoutProps } from '@/types';

export default function AuthSimpleLayout({
    children,
    title,
    description,
}: AuthLayoutProps) {
    return (
        <div className="flex min-h-svh flex-col items-center justify-center bg-muted p-6 md:p-10">
            <div className="w-full max-w-md">
                <div className="flex flex-col gap-10">
                    <div className="flex flex-col items-center gap-5">
                        <Link
                            href={home()}
                            className="flex flex-col items-center gap-3"
                        >
                            <div className="flex h-14 w-14 items-center justify-center rounded-xl bg-foreground shadow-sm">
                                <AppLogoIcon className="size-10 fill-current text-background" />
                            </div>
                            <span className="text-xl font-bold tracking-tight text-foreground">
                                Tribu Pakaras
                            </span>
                        </Link>

                        <div className="space-y-1.5 text-center">
                            <h1 className="text-xl font-semibold text-foreground">
                                {title}
                            </h1>
                            <p className="text-sm text-muted-foreground">
                                {description}
                            </p>
                        </div>
                    </div>

                    <div className="rounded-xl border border-border bg-card p-8 shadow-sm">
                        {children}
                    </div>
                </div>
            </div>
        </div>
    );
}
