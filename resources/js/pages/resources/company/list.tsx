import HeadingSmall from '@/components/heading-small';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import ResourcesLayout from '@/layouts/resources/layout';
import * as CompanyController from '@/routes/company';
import { type BreadcrumbItem } from '@/types';
import { CompanyNavItems, type companyModel } from '@/types/resources.d';
import { Head } from '@inertiajs/react';
import { TriangleAlert } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Company',
        href: CompanyController.index().url,
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
                                <div key={company.id} className="rounded-lg border border-border bg-card p-4 transition-colors hover:bg-accent/50">
                                    <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div className="flex-1 space-y-1">
                                            <Label className="text-base font-medium text-foreground">{company.name}</Label>
                                            <p className="text-sm text-muted-foreground">{company.description}</p>
                                        </div>
                                        <div className="flex gap-2">
                                            <Button size="sm" variant="outline" onClick={() => window.location.href = CompanyController.edit(company.id).url} className="min-w-[4rem]">
                                                Edit
                                            </Button>
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                onClick={() => window.location.href = CompanyController.destroy(company.id).url}
                                                className="min-w-[4rem] text-destructive hover:bg-destructive/10 hover:text-destructive"
                                            >
                                                Delete
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </>
                    )}
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}
