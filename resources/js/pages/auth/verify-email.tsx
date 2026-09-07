import { Form, Head } from '@inertiajs/react';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

export default function VerifyEmail({ status }: { status?: string }) {
    return (
        <>
            <Head title="Email verification" />

            {status === 'verification-link-sent' && (
                <div className="rounded-lg bg-success/10 p-3 text-center text-sm font-medium text-success">
                    A new verification link has been sent to your email address.
                </div>
            )}

            <div className="grid gap-5">
                <p className="text-center text-sm text-muted-foreground">
                    Before continuing, please verify your email address by clicking the link we just emailed to you.
                </p>

                <Form {...send.form()} className="flex flex-col gap-4">
                    {({ processing }) => (
                        <>
                            <Button
                                disabled={processing}
                                variant="secondary"
                                className="h-11 w-full text-sm font-semibold"
                            >
                                {processing && <Spinner />}
                                Resend verification email
                            </Button>

                            <div className="text-center text-sm text-muted-foreground">
                                <TextLink
                                    href={logout()}
                                    className="text-sm"
                                >
                                    Sign in with a different account
                                </TextLink>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </>
    );
}

VerifyEmail.layout = {
    title: 'Verify your email',
    description: 'Check your inbox for a verification link',
};
