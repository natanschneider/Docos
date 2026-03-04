import { Link } from "@inertiajs/react";
import { PencilIcon } from "lucide-react";

export default function HeadingSmall({
    title,
    description,
    editUrl
}: {
    title: string;
    description?: string;
    editUrl?: string;
}) {
    return (
        <header className="flex gap-2 items-center">
            <div className="flex flex-col gap-1">
                <h3 className="mb-0.5 text-base font-medium">{title}</h3>
                {description && <p className="text-sm text-muted-foreground">{description}</p>}
            </div>
            {editUrl && (
                <div className="flex gap-2 ml-4 items-center">
                    {editUrl && (
                        <Link href={editUrl}>
                            <PencilIcon className="w-4 h-4" />
                        </Link>
                    )}
                </div>
            )}
        </header>
    );
}
