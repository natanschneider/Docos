import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { AutoCloseAlert } from "@/components/auto-close-alert";
import axios from 'axios';
import { useState } from "react";
import { Link, router } from '@inertiajs/react';

interface resourceItem {
    id: number;
    name: string;
    description: string;
    edit_url: string;
    delete_url: string;
    list_url: string;
}

export default function ResourceListItem({ resource }: { resource: resourceItem }) {
    const [isVisible, setIsVisible] = useState(true);
    const [isOpen, setIsOpen] = useState(false);
    const [alertInfo, setAlertInfo] = useState<{
        type: 'success' | 'error';
        message: string;
        subMessage: string;
    }>({
        type: 'success',
        message: '',
        subMessage: ''
    });

    const deleteItem = async (delete_url: string, id: number) => {
        try {
            const response = await axios.delete(delete_url, {
                data: {
                    id: id,
                },
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                withCredentials: true
            });

            if (response.status === 200) {
                router.prefetch(resource.list_url, { method: 'get', fresh: true });

                setAlertInfo({
                    type: 'success',
                    message: 'Success',
                    subMessage: 'The resource has been deleted'
                });
                setIsOpen(true);

                setIsVisible(false);
            }
        } catch (error) {
            let msg = 'Something went wrong';
            let subMsg = 'Please try again later';

            if (axios.isAxiosError(error) && error.response?.data) {
                msg = error.response.data.message || msg;
                subMsg = error.response.data.errors || subMsg;
            }

            setAlertInfo({
                type: 'error',
                message: msg,
                subMessage: subMsg
            });
            setIsOpen(true);
        }
    };

    return (
        <>
        { isVisible && (
            <div key={resource.id} className="rounded-lg border border-border bg-card p-4 transition-colors hover:bg-accent/50">
                <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div className="flex-1 space-y-1">
                        <Label className="text-base font-medium text-foreground">{resource.name}</Label>
                        <p className="text-sm text-muted-foreground">{resource.description}</p>
                    </div>
                    <div className="flex gap-2">
                        <Button
                            size="sm"
                            variant="outline"
                            onClick={() => (window.location.href = resource.edit_url)}
                            className="min-w-[4rem]"
                        >
                            <Link href={resource.edit_url} prefetch>Edit</Link>
                        </Button>
                        <AlertDialog>
                            <AlertDialogTrigger asChild>
                                <Button
                                    size="sm"
                                    variant="outline"
                                    className="min-w-[4rem] text-destructive hover:bg-destructive/10 hover:text-destructive"
                                >
                                    Delete
                                </Button>
                            </AlertDialogTrigger>
                            <AlertDialogContent>
                                <AlertDialogHeader>
                                    <AlertDialogTitle>Are you absolutely sure?</AlertDialogTitle>
                                    <AlertDialogDescription>
                                        This action cannot be undone. This will permanently delete and remove the data.
                                    </AlertDialogDescription>
                                </AlertDialogHeader>
                                <AlertDialogFooter>
                                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                                    <AlertDialogAction className='text-destructive' onClick={() => deleteItem(resource.delete_url, resource.id) }>Delete</AlertDialogAction>
                                </AlertDialogFooter>
                            </AlertDialogContent>
                        </AlertDialog>
                    </div>
                </div>
            </div>
        ) }
            <AutoCloseAlert
                isOpen={isOpen}
                onClose={() => setIsOpen(false)}
                title={alertInfo.message}
                description={alertInfo.subMessage}
                autoCloseDelay={3000}
                variant={alertInfo.type}
            />
        </>
    );
}
