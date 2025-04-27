
import React from "react";
import { Link } from "react-router-dom";
import { Home, Package, Users, Settings, ShoppingCart } from "lucide-react";

const AdminSidebar = () => {
  return (
    <aside className="w-64 min-h-screen bg-gray-900 text-white">
      <div className="p-6">
        <Link to="/admin" className="text-xl font-bold text-white flex items-center">
          <span className="mr-2">🏥</span> Admin Panel
        </Link>
      </div>
      
      <nav className="mt-6">
        <div className="px-4">
          <ul className="space-y-1">
            <li>
              <Link
                to="/admin"
                className="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition"
              >
                <Home className="mr-3 h-5 w-5" />
                Dashboard
              </Link>
            </li>
            
            <li>
              <Link
                to="/admin/products"
                className="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition"
              >
                <Package className="mr-3 h-5 w-5" />
                Products
              </Link>
            </li>
            
            <li>
              <Link
                to="/admin/orders"
                className="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition"
              >
                <ShoppingCart className="mr-3 h-5 w-5" />
                Orders
              </Link>
            </li>
            
            <li>
              <Link
                to="/admin/users"
                className="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition"
              >
                <Users className="mr-3 h-5 w-5" />
                Users
              </Link>
            </li>
            
            <li>
              <Link
                to="/admin/settings"
                className="flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800 hover:text-white rounded-md transition"
              >
                <Settings className="mr-3 h-5 w-5" />
                Settings
              </Link>
            </li>
          </ul>
        </div>
      </nav>
      
      <div className="absolute bottom-0 w-full p-4 border-t border-gray-800">
        <Link
          to="/"
          className="flex items-center text-gray-300 hover:text-white transition"
        >
          <span>View Store</span>
        </Link>
      </div>
    </aside>
  );
};

export default AdminSidebar;
