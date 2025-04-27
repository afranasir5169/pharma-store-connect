
import React from "react";
import { Bell, Search, User } from "lucide-react";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { useToast } from "@/hooks/use-toast";

const AdminHeader = () => {
  const { toast } = useToast();

  return (
    <header className="bg-white border-b sticky top-0 z-30">
      <div className="px-6 py-4 flex items-center justify-between">
        <div className="w-72">
          <div className="relative">
            <Search className="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input
              placeholder="Search..."
              className="pl-8 w-full"
              onChange={() => 
                toast({
                  title: "Search",
                  description: "Search functionality coming soon",
                })
              }
            />
          </div>
        </div>
        
        <div className="flex items-center">
          <Button variant="ghost" size="icon" onClick={() => 
            toast({
              title: "Notifications",
              description: "You have no new notifications",
            })
          }>
            <Bell className="h-5 w-5" />
          </Button>
          
          <div className="ml-4 flex items-center">
            <div className="h-8 w-8 rounded-full bg-primary flex items-center justify-center text-primary-foreground">
              <User className="h-5 w-5" />
            </div>
            <span className="ml-2 font-medium text-sm">Admin User</span>
          </div>
        </div>
      </div>
    </header>
  );
};

export default AdminHeader;
