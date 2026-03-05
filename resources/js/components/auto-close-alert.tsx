import { AlertCircle, CheckCircle2, Info, XCircle } from 'lucide-react';
import { useEffect } from 'react';

interface AutoCloseAlertProps {
    isOpen: boolean;
    onClose: () => void;
    title: string;
    description?: string;
    autoCloseDelay?: number;
    variant?: 'success' | 'error' | 'warning' | 'info';
}

export function AutoCloseAlert({ isOpen, onClose, title, description, autoCloseDelay = 5000, variant = 'success' }: AutoCloseAlertProps) {
    useEffect(() => {
        if (isOpen && autoCloseDelay > 0) {
            const timer = setTimeout(() => {
                onClose();
            }, autoCloseDelay);

            return () => clearTimeout(timer);
        }
    }, [isOpen, autoCloseDelay, onClose]);

    if (!isOpen) return null;

    const icons = {
        success: <CheckCircle2 className="h-6 w-6 text-green-500" />,
        error: <XCircle className="h-6 w-6 text-red-500" />,
        warning: <AlertCircle className="h-6 w-6 text-yellow-500" />,
        info: <Info className="h-6 w-6 text-blue-500" />,
    };

    const borderColors = {
        success: 'border-green-500',
        error: 'border-red-500',
        warning: 'border-yellow-500',
        info: 'border-blue-500',
    };

    return (
        <>
            {/* Backdrop overlay */}
            <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm duration-200 animate-in fade-in" onClick={onClose} aria-hidden="true" />

            {/* Alert dialog */}
            <div
                className="fixed top-1/2 left-1/2 z-50 -translate-x-1/2 -translate-y-1/2 duration-200 animate-in fade-in zoom-in-95"
                onClick={onClose}
            >
                <div
                    className={`border-2 bg-background ${borderColors[variant]} mx-4 w-full max-w-md rounded-lg p-6 shadow-lg`}
                    onClick={(e) => e.stopPropagation()}
                >
                    <div className="flex items-start gap-4">
                        <div className="shrink-0">{icons[variant]}</div>
                        <div className="flex-1 space-y-2">
                            <h3 className="text-lg font-semibold">{title}</h3>
                            {description && <p className="text-sm text-muted-foreground">{description}</p>}
                        </div>
                    </div>

                    {/* Progress bar for auto-close */}
                    {autoCloseDelay > 0 && (
                        <div className="mt-4 h-1 overflow-hidden rounded-full bg-muted">
                            <div
                                className="animate-progress h-full bg-primary"
                                style={{
                                    animation: `progress ${autoCloseDelay}ms linear forwards`,
                                }}
                            />
                        </div>
                    )}
                </div>
            </div>

            <style>{`
                @keyframes progress {
                    from {
                        width: 100%;
                    }
                    to {
                        width: 0%;
                    }
                }
            `}</style>
        </>
    );
}
