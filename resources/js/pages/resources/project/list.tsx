import HeadingSmall from '@/components/heading-small';
import ResourceListItem from '@/components/resource-list-item';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import * as ProjectRoute from '@/routes/project';
import { type BreadcrumbItem } from '@/types';
import { ProjectNavItems, type projectModel } from '@/types/resources.d';
import { Head } from '@inertiajs/react';
import { TriangleAlert } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Project',
        href: ProjectRoute.index().url,
    },
];

export default function ListCompanies({ projects }: { projects: projectModel[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List of projects" />

            <ResourcesLayout title="Project" description="Manage your project information" sidebarNavItems={ProjectNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    {projects.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-16 text-center">
                            <div className="mb-4 rounded-full bg-muted p-6">
                                <TriangleAlert className="h-12 w-12 text-muted-foreground" />
                            </div>
                            <h3 className="mb-2 text-xl font-semibold text-foreground">No Projects Found</h3>
                            <p className="text-sm text-muted-foreground">There are no projects available at the moment.</p>
                            <p className="mt-1 text-xs text-muted-foreground/70">Please add a project to get started.</p>
                        </div>
                    ) : (
                        <>
                            <HeadingSmall title="Projects" description="List of all projects" />

                            {projects.map((project) => (
                                <ResourceListItem
                                    key={project.id}
                                    resource={ {
                                        id: project.id,
                                        name: project.name,
                                        edit_url: ProjectRoute.edit(project.id).url,
                                        delete_url: ProjectRoute.destroy(project.id).url,
                                        list_url: ProjectRoute.index().url,
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
