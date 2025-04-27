
import React, { useState } from "react";
import AdminLayout from "@/components/layouts/AdminLayout";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Search } from "lucide-react";
import OrderTable from "@/components/ui/custom/admin/OrderTable";

const AdminOrders = () => {
  const [searchQuery, setSearchQuery] = useState("");
  const [statusFilter, setStatusFilter] = useState("");
  
  const orders = [
    {
      id: "ORD12345",
      customer: "John Doe",
      date: "2025-04-26",
      total: 75.99,
      status: "Processing",
      items: 3,
    },
    {
      id: "ORD12346",
      customer: "Jane Smith",
      date: "2025-04-25",
      total: 120.50,
      status: "Completed",
      items: 2,
    },
    {
      id: "ORD12347",
      customer: "Michael Johnson",
      date: "2025-04-25",
      total: 45.25,
      status: "Draft",
      items: 1,
    },
    {
      id: "ORD12348",
      customer: "Robert Brown",
      date: "2025-04-24",
      total: 89.75,
      status: "Processing",
      items: 4,
    },
    {
      id: "ORD12349",
      customer: "Emily Wilson",
      date: "2025-04-24",
      total: 32.50,
      status: "Cancelled",
      items: 1,
    },
    {
      id: "ORD12350",
      customer: "David Miller",
      date: "2025-04-23",
      total: 150.25,
      status: "Completed",
      items: 5,
    },
    {
      id: "ORD12351",
      customer: "Sarah Anderson",
      date: "2025-04-23",
      total: 65.00,
      status: "Return",
      items: 2,
    },
  ];
  
  const filteredOrders = orders.filter(order => 
    (order.id.toLowerCase().includes(searchQuery.toLowerCase()) ||
     order.customer.toLowerCase().includes(searchQuery.toLowerCase())) &&
    (statusFilter === "" || order.status === statusFilter)
  );

  return (
    <AdminLayout>
      <h1 className="text-2xl font-bold mb-6">Orders</h1>
      
      <div className="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <div className="p-4 border-b flex flex-col sm:flex-row gap-4">
          <div className="relative flex-grow">
            <Search className="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input
              placeholder="Search by order ID or customer name..."
              className="pl-8"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />
          </div>
          
          <div className="sm:w-64">
            <Select value={statusFilter} onValueChange={setStatusFilter}>
              <SelectTrigger>
                <SelectValue placeholder="All Statuses" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="">All Statuses</SelectItem>
                <SelectItem value="Draft">Draft</SelectItem>
                <SelectItem value="Processing">Processing</SelectItem>
                <SelectItem value="Completed">Completed</SelectItem>
                <SelectItem value="Return">Return</SelectItem>
                <SelectItem value="Cancelled">Cancelled</SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>
        
        <OrderTable orders={filteredOrders} />
      </div>
    </AdminLayout>
  );
};

export default AdminOrders;
