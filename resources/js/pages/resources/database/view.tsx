import HeadingSmall from "@/components/heading-small";
import AppLayout from "@/layouts/app-layout";
import ResourcesLayout from "@/layouts/resources/layout";
import database from "@/routes/database";
import { databaseModel, DatabaseNavItems, tableModel } from "@/types/resources.d";
import { type BreadcrumbItem } from "@/types";
import { Head, Link } from "@inertiajs/react";
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item";
import Diagram from "@/components/database-schema";
import company from "@/routes/company";

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
                        {database[0]?.company && (
                            <div className="grid gap-2">
                                <Link href={company.show(database[0].company.id).url}>
                                    <Item variant='outline'>
                                        <ItemContent>
                                            <ItemTitle>Company</ItemTitle>
                                            <ItemDescription>{database[0].company.name ?? ''}</ItemDescription>
                                        </ItemContent>
                                    </Item>
                                </Link>
                            </div>
                        )}

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
