import HeadingSmall from '@/components/heading-small';
import ResourceListItem from '@/components/resource-list-item';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import * as CompanyRoute from '@/routes/company';
import { type BreadcrumbItem } from '@/types';
import { CompanyNavItems, type companyModel } from '@/types/resources.d';
import { Head } from '@inertiajs/react';
import { TriangleAlert } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Company',
        href: CompanyRoute.index().url,
    },
];

export default function ListCompanies({ companies }: { companies: companyModel[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="List of companies" />

            <ResourcesLayout title="Company" description="Manage your company information" sidebarNavItems={CompanyNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    {companies.length === 0 ? (
                        <div className="flex flex-col items-center justify-center py-16 text-center">
                            <div className="mb-4 rounded-full bg-muted p-6">
                                <TriangleAlert className="h-12 w-12 text-muted-foreground" />
                            </div>
                            <h3 className="mb-2 text-xl font-semibold text-foreground">No Companies Found</h3>
                            <p className="text-sm text-muted-foreground">There are no companies available at the moment.</p>
                            <p className="mt-1 text-xs text-muted-foreground/70">Please add a company to get started.</p>
                        </div>
                    ) : (
                        <>
                            <HeadingSmall title="Companies" description="List of all companies" />

                            {companies.map((company) => (
                                <ResourceListItem
                                    key={company.id}
                                    resource={ {
                                        id: company.id,
                                        name: company.name,
                                        description: company.description,
                                        edit_url: CompanyRoute.edit(company.id).url,
                                        delete_url: CompanyRoute.destroy(company.id).url
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
