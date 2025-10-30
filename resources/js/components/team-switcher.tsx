import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import { changeCompany } from '@/routes';
import { create } from '@/routes/company';
import { type SharedData } from '@/types';
import { Link, router, usePage } from '@inertiajs/react';
import { ChevronsUpDown, Plus } from 'lucide-react';
import * as React from 'react';

export function TeamSwitcher({
    teams,
}: {
    teams: Record<string, { name: string; id: number }>;
}) {
    const { currentCompany } = usePage<SharedData>().props;
    const { isMobile } = useSidebar();
    const [activeCompany, setActiveCompany] = React.useState(teams[currentCompany ?? 0]);

    if (!activeCompany) {
        return null;
    }

    const changeComp = (id: number) => {
        setActiveCompany(teams[id]);
        router.flushAll();
        router.get(changeCompany(id));
    };

    return (
        <SidebarMenu>
            <SidebarMenuItem>
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <SidebarMenuButton size="lg" className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
                            <div className="grid flex-1 text-left text-sm leading-tight">
                                <span className="truncate font-medium">{activeCompany.name}</span>
                            </div>
                            <ChevronsUpDown className="ml-auto" />
                        </SidebarMenuButton>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        className="w-(--radix-dropdown-menu-trigger-width) min-w-56 rounded-lg"
                        align="start"
                        side={isMobile ? 'bottom' : 'right'}
                        sideOffset={4}
                    >
                        <DropdownMenuLabel className="text-xs text-muted-foreground">Companies</DropdownMenuLabel>
                        {Object.values(teams).map((team, index) => (
                            <DropdownMenuItem key={team.name} onClick={() => changeComp(team.id)} className="gap-2 p-2">
                                {team.name}
                                <DropdownMenuShortcut>⌘{index + 1}</DropdownMenuShortcut>
                            </DropdownMenuItem>
                        ))}
                        <DropdownMenuSeparator />
                        <DropdownMenuItem className="gap-2 p-2">
                            <div className="flex size-6 items-center justify-center rounded-md border bg-transparent">
                                <Plus className="size-4" />
                            </div>
                            <Link href={create().url} className="font-medium text-muted-foreground">
                                Add company
                            </Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </SidebarMenuItem>
        </SidebarMenu>
    );
}
