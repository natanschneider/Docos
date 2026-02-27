import HeadingSmall from '@/components/heading-small';
import ProjectSidebarSelect from '@/components/project-sidebar-select';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import application from '@/routes/application';
import database from '@/routes/database';
import screen from '@/routes/screen';
import endpoint from '@/routes/endpoint';
import { type BreadcrumbItem } from '@/types';
import { ApplicationNavItems, databaseModel, projectModel, type applicationModel } from '@/types/resources.d';
import { Head, Link } from '@inertiajs/react';
import { Item, ItemContent, ItemDescription, ItemTitle } from '@/components/ui/item';
import project from '@/routes/project';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Application',
        href: application.index().url,
    },
];

export default function ViewApplication({
    application,
    databases,
    projects,
}: {
    application: applicationModel;
    databases: databaseModel[];
    projects: projectModel[];
}) {
    const items = application !== null
        ? application?.databases.map((db) => db.id.toString())
        : [];

    const databaseArr = databases.reduce(
        (acc, database) => {
            acc[database.id] = database.name;
            return acc;
        },
        {} as Record<string, string>,
    );

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title='View application' />

            <ResourcesLayout
                title="Application"
                description="Manage your application information"
                sidebarNavItems={ApplicationNavItems}
                sidebarExtraNavItems={ProjectSidebarSelect(projects)}
            >
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title='View application'
                    />

                    <div className='grid gap-2'>
                        <Link href={project.show(application.project_id)}>
                            <Item variant="outline">
                                <ItemContent>
                                    <ItemTitle>Project</ItemTitle>
                                    <ItemDescription>{application.project !== null ? application?.project?.name : undefined}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </Link>

                        <Item variant="outline">
                            <ItemContent>
                                <ItemTitle>Name</ItemTitle>
                                <ItemDescription>{application !== null ? application?.name : undefined}</ItemDescription>
                            </ItemContent>
                        </Item>
                    </div>

                    {items.length > 0 && (
                        <div className="grid gap-2">
                            <div className="flex w-[350px] flex-col gap-2">
                                <div className="flex items-center justify-between gap-4 px-4">
                                    <Label htmlFor="database_id">Databases</Label>
                                </div>

                                {items.map((item, index) => (
                                    <Link href={ database.show(item) } className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                        <p className="grow">{databaseArr[item]}</p>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}

                    {application.screens !== null && application?.screens.length > 0 && (
                        <div className="grid gap-2">
                            <div className="flex w-[350px] flex-col gap-2">
                                <div className="flex items-center justify-between gap-4 px-4">
                                    <Label htmlFor="screen_id">Screens</Label>
                                </div>

                                {application.screens.map((item, index) => (
                                    <Link href={screen.show(item.id)} className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                        <p className="grow">{item.name}</p>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}

                    {application.endpoints !== null && application?.endpoints.length > 0 && (
                        <div className="grid gap-2">
                            <div className="flex w-[350px] flex-col gap-2">
                                <div className="flex items-center justify-between gap-4 px-4">
                                    <Label htmlFor="screen_id">Endpoints</Label>
                                </div>

                                {application.endpoints.map((item, index) => (
                                    <Link href={endpoint.show(item.id)} className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                        <p className="grow">{item.name}</p>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}
