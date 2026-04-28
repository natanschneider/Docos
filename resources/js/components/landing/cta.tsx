import { login, register } from '@/routes';
import { Link } from '@inertiajs/react';

export function CTA() {
    return (
        <section className="border-t border-border bg-foreground py-20">
            <div className="mx-auto max-w-5xl px-4 text-center">
                <h2 className="text-3xl font-bold tracking-tight text-background md:text-4xl">Ready to document your data?</h2>
                <p className="mx-auto mt-4 max-w-xl text-background/70">
                    Start for free in less than 5 minutes.
                    <br />
                    No complex setup. No credit card required.
                </p>
                <div className="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row">
                    <Link
                        href={login()}
                        className="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal dark:text-[#1b1b18] dark:hover:border-[#19140035] text-[#EDEDEC] hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        href={register()}
                        className="inline-block rounded-sm border  dark:border-[#19140035] px-5 py-1.5 text-sm leading-normal  dark:text-[#1b1b18]  dark:hover:border-[#1915014a] border-[#3E3E3A] text-[#EDEDEC] hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </div>
            </div>
        </section>
    );
}
