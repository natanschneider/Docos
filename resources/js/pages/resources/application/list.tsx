import HeadingSmall from '@/components/heading-small';
import ProjectSidebarSelect from '@/components/project-sidebar-select';
import ResourceListItem from '@/components/resource-list-item';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import * as ApplicationRoute from '@/routes/application';
import { type BreadcrumbItem } from '@/types';
import { ApplicationNavItems, projectModel, type applicationModel } from '@/types/resources.d';
import { Head } from '@inertiajs/react';
import { TriangleAlert } from 'lucide-react';
import { Button } from "@/components/ui/button"
import { ButtonGroup } from "@/components/ui/button-group"
import { Field, FieldLabel } from "@/components/ui/field"
import { Input } from "@/components/ui/input"
import { useMemo, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Application',
        href: ApplicationRoute.index().url,
    },
];

export default function ListApplications({ applications, projects }: { applications: applicationModel[]; projects: projectModel[] }) {
    const [query, setQuery] = useState('');

    const filtered = useMemo(() => {
        const q = query.trim().toLowerCase();
        if (!q) return applications;

        return applications.filter((application) => {
            return application.name.toLowerCase().includes(q);
        })
    }, [applications, query]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List of applications" />

            <ResourcesLayout
                title="Application"
                description="Manage your application information"
                sidebarNavItems={ApplicationNavItems}
                sidebarExtraNavItems={ProjectSidebarSelect(projects)}
            >
                <div className="border-spacing-x-60 space-y-6">
                    {applications.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-16 text-center">
                            <div className="mb-4 rounded-full bg-muted p-6">
                                <TriangleAlert className="h-12 w-12 text-muted-foreground" />
                            </div>
                            <h3 className="mb-2 text-xl font-semibold text-foreground">No Applications Found</h3>
                            <p className="text-sm text-muted-foreground">There are no applications available at the moment.</p>
                            <p className="mt-1 text-xs text-muted-foreground/70">Please add a application to get started.</p>
                        </div>
                    ) : (
                        <>
                            <HeadingSmall title="Applications" description="List of all applications" />

                            <Field>
                                <FieldLabel htmlFor="input-button-group">Search</FieldLabel>
                                <ButtonGroup>
                                    <Input
                                        id="input-button-group"
                                        placeholder="Type to search..."
                                        value={query}
                                        onChange={(e) => setQuery(e.target.value)}
                                    />
                                    <Button variant="outline">Search</Button>
                                </ButtonGroup>
                            </Field>

                            {filtered.map((application) => (
                                <ResourceListItem
                                    key={application.id}
                                    resource={{
                                        id: application.id,
                                        name: application.name,
                                        view_url: ApplicationRoute.show(application.id).url,
                                        edit_url: ApplicationRoute.edit(application.id).url,
                                        delete_url: ApplicationRoute.destroy(application.id).url,
                                        list_url: ApplicationRoute.index().url,
                                    }}
                                />
                            ))}
                        </>
                    )}
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}
