import ApplicationSidebarSelect from '@/components/application-sidebar-select';
import HeadingSmall from '@/components/heading-small';
import ResourceListItem from '@/components/resource-list-item';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import * as EndpointRoute from '@/routes/endpoint';
import { type BreadcrumbItem } from '@/types';
import { applicationModel, projectModel, EndpointNavItems, type endpointModel } from '@/types/resources.d';
import { Head } from '@inertiajs/react';
import { TriangleAlert } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Endpoint',
        href: EndpointRoute.index().url,
    },
];

export default function ListEndpoints({ endpoints, projects, applications }: { endpoints: endpointModel[]; projects: projectModel[]; applications: applicationModel[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List of endpoints" />

            <ResourcesLayout
                title="Endpoints"
                description="Manage your endpoints information"
                sidebarNavItems={EndpointNavItems}
                sidebarExtraNavItems={ApplicationSidebarSelect({location: 'endpoint', projects, applications})}
            >
                <div className="border-spacing-x-60 space-y-6">
                    {endpoints.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-16 text-center">
                            <div className="mb-4 rounded-full bg-muted p-6">
                                <TriangleAlert className="h-12 w-12 text-muted-foreground" />
                            </div>
                            <h3 className="mb-2 text-xl font-semibold text-foreground">No Endpoints Found</h3>
                            <p className="text-sm text-muted-foreground">There are no endpoints available at the moment.</p>
                            <p className="mt-1 text-xs text-muted-foreground/70">Please add a endpoint to get started.</p>
                        </div>
                    ) : (
                        <>
                            <HeadingSmall title="Endpoints" description="List of all Endpoints" />

                            {endpoints.map((endpoint) => (
                                <ResourceListItem
                                    key={endpoint.id}
                                    resource={{
                                        id: endpoint.id,
                                        name: endpoint.name,
                                        view_url: EndpointRoute.show(endpoint.id).url,
                                        edit_url: EndpointRoute.edit(endpoint.id).url,
                                        delete_url: EndpointRoute.destroy(endpoint.id).url,
                                        list_url: EndpointRoute.index().url,
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
