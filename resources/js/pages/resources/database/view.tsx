import HeadingSmall from "@/components/heading-small";
import AppLayout from "@/layouts/app-layout";
import ResourcesLayout from "@/layouts/resources/layout";
import database from "@/routes/database";
import { databaseModel, DatabaseNavItems, tableModel } from "@/types/resources.d";
import { type BreadcrumbItem } from "@/types";
import { Head } from "@inertiajs/react";
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item";
import Diagram from "@/components/database-schema";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Database',
        href: database.index().url
    }
];

export default function ViewDatabase({
    database,
    tables
}: {
    database: databaseModel[];
    tables: tableModel[];
}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View database" />

            <ResourcesLayout title="Database" description="Manage your database information" sidebarNavItems={DatabaseNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title="View database"
                    />

                    <div className="space-y-6">
                        <div className="grid gap-2">
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Name</ItemTitle>
                                    <ItemDescription>{database[0]?.name ?? ''}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </div>

                        <div className="grid gap-2">
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Engine</ItemTitle>
                                    <ItemDescription>{database[0]?.engine?.name ?? ''}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </div>

                        <Diagram tables={tables} />
                    </div>
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}
