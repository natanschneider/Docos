import HeadingSmall from "@/components/heading-small";
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item";
import { Label } from "@/components/ui/label";
import AppLayout from "@/layouts/app-layout";
import ResourcesLayout from "@/layouts/resources/layout";
import company from "@/routes/company";
import database from "@/routes/database";
import project from "@/routes/project";
import { BreadcrumbItem } from "@/types";
import { companyModel, CompanyNavItems } from "@/types/resources.d";
import { Head, Link } from "@inertiajs/react";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Company',
        href: company.index().url
    },
];

const companyRoute = company;

export default function ViewCompany({company}: {company: companyModel[]}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View company" />

            <ResourcesLayout title="Company" description="Manage your company information" sidebarNavItems={CompanyNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title="View company"
                        editUrl={companyRoute.edit(company[0]?.id).url}
                    />

                    <div className="space-y-6">
                        <div className="grid gap-2">
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Name</ItemTitle>
                                    <ItemDescription>{company[0]?.name ?? ''}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </div>

                        <div className="grid gap-2">
                            <Item variant='outline'>
                                <ItemContent>
                                    <ItemTitle>Description</ItemTitle>
                                    <ItemDescription>{company[0]?.description ?? ''}</ItemDescription>
                                </ItemContent>
                            </Item>
                        </div>

                        {company[0]?.databases && company[0]?.databases?.length > 0 && (
                            <div className="grid gap-2">
                                <div className="flex w-87.5 flex-col gap-2">
                                    <div className="flex items-center justify-between gap-4 px-4">
                                        <Label>Databases</Label>
                                    </div>

                                    {company[0]?.databases.map((item, index) => (
                                        <Link href={database.show(item.id)} className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                            <p className="grow">{item.name}</p>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}

                        {company[0]?.projects && company[0]?.projects?.length > 0 && (
                            <div className="grid gap-2">
                                <div className="flex w-87.5 flex-col gap-2">
                                    <div className="flex items-center justify-between gap-4 px-4">
                                        <Label>Projects</Label>
                                    </div>

                                    {company[0]?.projects.map((item, index) => (
                                        <Link href={project.show(item.id)} className="flex rounded-md border px-4 py-2 font-mono text-sm items-center" key={index}>
                                            <p className="grow">{item.name}</p>
                                        </Link>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}