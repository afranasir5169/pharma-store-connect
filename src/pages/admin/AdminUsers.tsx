
import React, { useState } from "react";
import AdminLayout from "@/components/layouts/AdminLayout";
import { Input } from "@/components/ui/input";
import { Search } from "lucide-react";
import UserTable from "@/components/ui/custom/admin/UserTable";
import { useToast } from "@/hooks/use-toast";

const AdminUsers = () => {
  const { toast } = useToast();
  const [searchQuery, setSearchQuery] = useState("");
  
  const [users, setUsers] = useState([
    {
      id: "USR001",
      name: "John Doe",
      email: "john.doe@example.com",
      joinDate: "2025-01-15",
      orders: 5,
    },
    {
      id: "USR002",
      name: "Jane Smith",
      email: "jane.smith@example.com",
      joinDate: "2025-02-20",
      orders: 3,
    },
    {
      id: "USR003",
      name: "Michael Johnson",
      email: "michael.johnson@example.com",
      joinDate: "2025-03-10",
      orders: 1,
    },
    {
      id: "USR004",
      name: "Emily Wilson",
      email: "emily.wilson@example.com",
      joinDate: "2025-03-25",
      orders: 0,
    },
    {
      id: "USR005",
      name: "David Miller",
      email: "david.miller@example.com",
      joinDate: "2025-04-05",
      orders: 2,
    },
    {
      id: "USR006",
      name: "Sarah Anderson",
      email: "sarah.anderson@example.com",
      joinDate: "2025-04-15",
      orders: 1,
    },
  ]);
  
  const handleDeleteUser = (userId: string) => {
    setUsers(users.filter(user => user.id !== userId));
  };
  
  const filteredUsers = users.filter(user => 
    user.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
    user.email.toLowerCase().includes(searchQuery.toLowerCase())
  );

  return (
    <AdminLayout>
      <h1 className="text-2xl font-bold mb-6">Users</h1>
      
      <div className="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <div className="p-4 border-b">
          <div className="relative">
            <Search className="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input
              placeholder="Search users by name or email..."
              className="pl-8"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />
          </div>
        </div>
        
        <UserTable users={filteredUsers} onDeleteUser={handleDeleteUser} />
      </div>
    </AdminLayout>
  );
};

export default AdminUsers;
