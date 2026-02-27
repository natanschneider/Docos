import HeadingSmall from "@/components/heading-small";
import { Item, ItemContent, ItemDescription, ItemTitle } from "@/components/ui/item";
import AppLayout from "@/layouts/app-layout";
import ResourcesLayout from "@/layouts/resources/layout";
import company from "@/routes/company";
import { BreadcrumbItem } from "@/types";
import { companyModel, CompanyNavItems } from "@/types/resources.d";
import { Head } from "@inertiajs/react";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Company',
        href: company.index().url
    },
];

export default function ViewCompany({company}: {company: companyModel[]}) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="View company" />

            <ResourcesLayout title="Company" description="Manage your company information" sidebarNavItems={CompanyNavItems}>
                <div className="border-spacing-x-60 space-y-6">
                    <HeadingSmall
                        title="View company"
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
                    </div>
                </div>
            </ResourcesLayout>
        </AppLayout>
    );
}