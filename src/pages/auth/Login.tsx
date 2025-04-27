
import React, { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { useToast } from "@/hooks/use-toast";

const Login = () => {
  const navigate = useNavigate();
  const { toast } = useToast();
  const [isAdminLogin, setIsAdminLogin] = useState(false);
  
  const [formData, setFormData] = useState({
    email: "",
    password: "",
  });
  
  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value,
    }));
  };
  
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    // Simple validation
    if (!formData.email || !formData.password) {
      toast({
        title: "Validation Error",
        description: "Please fill in all fields.",
        variant: "destructive",
      });
      return;
    }
    
    // Mock authentication
    toast({
      title: "Login Successful",
      description: `Welcome back${isAdminLogin ? " admin" : ""}!`,
    });
    
    // Redirect based on login type
    setTimeout(() => {
      navigate(isAdminLogin ? "/admin" : "/");
    }, 500);
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div className="max-w-md w-full space-y-8">
        <div className="text-center">
          <h1 className="text-3xl font-bold">PharmaConnect</h1>
          <p className="mt-2 text-gray-600">Sign in to your account</p>
        </div>
        
        <Card>
          <CardHeader>
            <div className="flex justify-center space-x-4">
              <button
                className={`px-4 py-2 ${isAdminLogin ? "text-gray-500" : "text-primary border-b-2 border-primary"}`}
                onClick={() => setIsAdminLogin(false)}
              >
                Customer
              </button>
              <button
                className={`px-4 py-2 ${isAdminLogin ? "text-primary border-b-2 border-primary" : "text-gray-500"}`}
                onClick={() => setIsAdminLogin(true)}
              >
                Admin
              </button>
            </div>
            <CardTitle>{isAdminLogin ? "Admin Login" : "Customer Login"}</CardTitle>
            <CardDescription>
              {isAdminLogin 
                ? "Access the admin dashboard to manage products, orders, and users." 
                : "Sign in to your account to view orders, save to wishlist, and more."}
            </CardDescription>
          </CardHeader>
          
          <form onSubmit={handleSubmit}>
            <CardContent className="space-y-4">
              <div className="space-y-2">
                <Label htmlFor="email">Email</Label>
                <Input
                  id="email"
                  name="email"
                  type="email"
                  placeholder="Enter your email"
                  value={formData.email}
                  onChange={handleChange}
                  required
                />
              </div>
              
              <div className="space-y-2">
                <div className="flex justify-between items-center">
                  <Label htmlFor="password">Password</Label>
                  <Link to="/forgot-password" className="text-xs text-primary hover:underline">
                    Forgot password?
                  </Link>
                </div>
                <Input
                  id="password"
                  name="password"
                  type="password"
                  placeholder="Enter your password"
                  value={formData.password}
                  onChange={handleChange}
                  required
                />
              </div>
            </CardContent>
            
            <CardFooter className="flex flex-col space-y-4">
              <Button type="submit" className="w-full bg-primary hover:bg-primary/90">
                Sign In
              </Button>
              
              {!isAdminLogin && (
                <div className="text-sm text-center">
                  Don't have an account?{" "}
                  <Link to="/register" className="text-primary hover:underline">
                    Register
                  </Link>
                </div>
              )}
            </CardFooter>
          </form>
        </Card>
      </div>
    </div>
  );
};

export default Login;
