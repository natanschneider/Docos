import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/components/ui/select';
import { changeApplication as changeApp } from '@/routes';
import { type SharedData } from '@/types';
import { applicationModel } from '@/types/resources';
import { router, usePage } from '@inertiajs/react';
import { useState } from 'react';

export default function ApplicationSidebarSelect(applications: applicationModel[]) {
    const { currentApplication } = usePage<SharedData>().props;
    const [application, setApplication] = useState<string>(currentApplication?.toString() ?? applications[0]?.id?.toString());

    const changeApplication = (value: string) => {
        setApplication(value);
        router.flushAll();
        router.get(changeApp(value));
    };

    return (
        <div className="w-full justify-start my-4">
            <Label htmlFor="application">Application</Label>

            <input type="hidden" name="application" value={application} />
            <Select onValueChange={(value) => changeApplication(value)} defaultValue={application}>
                <SelectTrigger className="w-[180px]">
                    <SelectValue placeholder="Select an application" />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectLabel>Application</SelectLabel>
                        {applications.map((application) => (
                            <SelectItem key={application.id} value={application.id.toString()}>
                                {application.name}
                            </SelectItem>
                        ))}
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
    );
}
