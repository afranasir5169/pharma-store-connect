
import React from "react";
import AdminLayout from "@/components/layouts/AdminLayout";
import AdminCard from "@/components/ui/custom/admin/AdminCard";
import OrderTable from "@/components/ui/custom/admin/OrderTable";
import { ShoppingCart, User, Package, DollarSign } from "lucide-react";
import { Card, CardContent } from "@/components/ui/card";
import { 
  LineChart, Line, CartesianGrid, XAxis, YAxis, Tooltip, ResponsiveContainer 
} from 'recharts';

const AdminDashboard = () => {
  const data = [
    { name: 'Jan', revenue: 4000 },
    { name: 'Feb', revenue: 3000 },
    { name: 'Mar', revenue: 5000 },
    { name: 'Apr', revenue: 8000 },
    { name: 'May', revenue: 6000 },
    { name: 'Jun', revenue: 9000 },
  ];
  
  const recentOrders = [
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
  ];

  return (
    <AdminLayout>
      <h1 className="text-2xl font-bold mb-6">Dashboard</h1>
      
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <AdminCard
          title="Total Orders"
          value="156"
          icon={<ShoppingCart className="h-5 w-5 text-white" />}
          trend={{ value: 12.5, isPositive: true }}
          color="bg-blue-500"
        />
        
        <AdminCard
          title="Total Customers"
          value="84"
          icon={<User className="h-5 w-5 text-white" />}
          trend={{ value: 8.2, isPositive: true }}
          color="bg-green-500"
        />
        
        <AdminCard
          title="Products"
          value="32"
          icon={<Package className="h-5 w-5 text-white" />}
          color="bg-purple-500"
        />
        
        <AdminCard
          title="Revenue"
          value="$4,325.50"
          icon={<DollarSign className="h-5 w-5 text-white" />}
          trend={{ value: 5.8, isPositive: true }}
          color="bg-yellow-500"
        />
      </div>
      
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <Card>
          <CardContent className="pt-6">
            <h2 className="text-lg font-semibold mb-4">Revenue Overview</h2>
            <ResponsiveContainer width="100%" height={300}>
              <LineChart data={data} margin={{ top: 5, right: 30, left: 20, bottom: 5 }}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="name" />
                <YAxis />
                <Tooltip />
                <Line type="monotone" dataKey="revenue" stroke="#4ECDC4" activeDot={{ r: 8 }} />
              </LineChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>
        
        <Card>
          <CardContent className="pt-6">
            <h2 className="text-lg font-semibold mb-4">Sales by Category</h2>
            <ResponsiveContainer width="100%" height={300}>
              <LineChart data={data} margin={{ top: 5, right: 30, left: 20, bottom: 5 }}>
                <CartesianGrid strokeDasharray="3 3" />
                <XAxis dataKey="name" />
                <YAxis />
                <Tooltip />
                <Line type="monotone" dataKey="revenue" stroke="#FF6B6B" activeDot={{ r: 8 }} />
              </LineChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>
      </div>
      
      <Card className="mb-8">
        <CardContent className="pt-6">
          <h2 className="text-lg font-semibold mb-4">Recent Orders</h2>
          <OrderTable orders={recentOrders} />
        </CardContent>
      </Card>
    </AdminLayout>
  );
};

export default AdminDashboard;
