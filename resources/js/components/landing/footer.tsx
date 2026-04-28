import AppLogo from "@/components/app-logo";

export function Footer() {
    const year = new Date().getFullYear();

    return (
        <footer className="border-t border-border py-12">
            <div className="mx-auto max-w-5xl px-4">
                <div className="flex flex-col items-center justify-between gap-6 md:flex-row">
                    <div className="flex items-center gap-2">
                        <div className="flex size-8 items-center justify-center rounded-md bg-foreground">
                            <AppLogo />
                        </div>
                        <span className="text-lg font-semibold tracking-tight">Docos</span>
                    </div>

                    <p className="text-sm text-muted-foreground">© { year } <a href="https://www.natanmoura.com.br">Natan Moura</a>. All rights reserved.</p>
                </div>
            </div>
        </footer>
    );
}
