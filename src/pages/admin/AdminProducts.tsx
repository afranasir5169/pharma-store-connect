
import React, { useState } from "react";
import AdminLayout from "@/components/layouts/AdminLayout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Edit, Trash, Plus, Search } from "lucide-react";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
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
} from "@/components/ui/alert-dialog";
import ProductForm from "@/components/ui/custom/admin/ProductForm";
import { useToast } from "@/hooks/use-toast";

const AdminProducts = () => {
  const { toast } = useToast();
  const [searchQuery, setSearchQuery] = useState("");
  const [currentCategory, setCurrentCategory] = useState("");
  
  const products = [
    {
      id: 1,
      name: "Hatch Baby Rest Night Light",
      category: "Devices",
      price: 55,
      stock: 25,
      sku: "HBR-2023",
      requiresPrescription: false,
    },
    {
      id: 2,
      name: "Biotin Complex with Coconut Oil",
      category: "Vitamins & Supplements",
      price: 30,
      stock: 120,
      sku: "BIO-5000-COO",
      requiresPrescription: false,
    },
    {
      id: 3,
      name: "Vitamin D3 Gummies, Blueberry Taste",
      category: "Vitamins & Supplements",
      price: 40,
      stock: 85,
      sku: "VIT-D3-GUM",
      requiresPrescription: false,
    },
    {
      id: 4,
      name: "Blood Pressure Monitor",
      category: "Devices",
      price: 24,
      stock: 15,
      sku: "BPM-202X",
      requiresPrescription: false,
    },
    {
      id: 5,
      name: "Antibiotic Ointment",
      category: "Medications",
      price: 12.99,
      stock: 50,
      sku: "MED-AB-OIN",
      requiresPrescription: true,
    },
  ];
  
  const handleDelete = (id: number) => {
    toast({
      title: "Product Deleted",
      description: `Product #${id} has been deleted.`,
    });
  };
  
  const handleCreateProduct = (data: any) => {
    toast({
      title: "Product Created",
      description: `${data.name} has been created successfully.`,
    });
  };
  
  const handleUpdateProduct = (data: any) => {
    toast({
      title: "Product Updated",
      description: `${data.name} has been updated successfully.`,
    });
  };
  
  const filteredProducts = products.filter(product => 
    product.name.toLowerCase().includes(searchQuery.toLowerCase()) &&
    (currentCategory === "" || product.category === currentCategory)
  );

  return (
    <AdminLayout>
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-2xl font-bold">Products</h1>
        
        <Dialog>
          <DialogTrigger asChild>
            <Button className="bg-primary hover:bg-primary/90">
              <Plus className="mr-2 h-4 w-4" />
              Add Product
            </Button>
          </DialogTrigger>
          <DialogContent className="max-w-3xl">
            <DialogHeader>
              <DialogTitle>Create New Product</DialogTitle>
            </DialogHeader>
            <div className="max-h-[70vh] overflow-y-auto p-1">
              <ProductForm onSubmit={handleCreateProduct} />
            </div>
          </DialogContent>
        </Dialog>
      </div>
      
      <div className="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <div className="p-4 border-b flex flex-col sm:flex-row gap-4">
          <div className="relative flex-grow">
            <Search className="absolute left-2 top-2.5 h-4 w-4 text-muted-foreground" />
            <Input
              placeholder="Search products..."
              className="pl-8"
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
            />
          </div>
          
          <div className="sm:w-64">
            <Select value={currentCategory} onValueChange={setCurrentCategory}>
              <SelectTrigger>
                <SelectValue placeholder="All Categories" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="">All Categories</SelectItem>
                <SelectItem value="Vitamins & Supplements">Vitamins & Supplements</SelectItem>
                <SelectItem value="Medications">Medications</SelectItem>
                <SelectItem value="First Aid">First Aid</SelectItem>
                <SelectItem value="Devices">Devices</SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>
        
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>ID</TableHead>
              <TableHead>Product Name</TableHead>
              <TableHead>Category</TableHead>
              <TableHead>Price</TableHead>
              <TableHead>Stock</TableHead>
              <TableHead>SKU</TableHead>
              <TableHead>Prescription</TableHead>
              <TableHead className="text-right">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {filteredProducts.map((product) => (
              <TableRow key={product.id}>
                <TableCell className="font-medium">#{product.id}</TableCell>
                <TableCell>{product.name}</TableCell>
                <TableCell>{product.category}</TableCell>
                <TableCell>${product.price}</TableCell>
                <TableCell>{product.stock}</TableCell>
                <TableCell>{product.sku}</TableCell>
                <TableCell>{product.requiresPrescription ? "Yes" : "No"}</TableCell>
                <TableCell className="text-right">
                  <div className="flex justify-end space-x-2">
                    <Dialog>
                      <DialogTrigger asChild>
                        <Button variant="outline" size="sm">
                          <Edit className="h-4 w-4" />
                        </Button>
                      </DialogTrigger>
                      <DialogContent className="max-w-3xl">
                        <DialogHeader>
                          <DialogTitle>Edit Product</DialogTitle>
                        </DialogHeader>
                        <div className="max-h-[70vh] overflow-y-auto p-1">
                          <ProductForm
                            initialData={{
                              id: product.id,
                              name: product.name,
                              price: product.price,
                              category: product.category,
                              sku: product.sku,
                              requiresPrescription: product.requiresPrescription,
                              stock: product.stock,
                              description: "Product description goes here.",
                            }}
                            onSubmit={handleUpdateProduct}
                          />
                        </div>
                      </DialogContent>
                    </Dialog>
                    
                    <AlertDialog>
                      <AlertDialogTrigger asChild>
                        <Button variant="destructive" size="sm">
                          <Trash className="h-4 w-4" />
                        </Button>
                      </AlertDialogTrigger>
                      <AlertDialogContent>
                        <AlertDialogHeader>
                          <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                          <AlertDialogDescription>
                            This will permanently delete {product.name}. This action cannot be undone.
                          </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                          <AlertDialogCancel>Cancel</AlertDialogCancel>
                          <AlertDialogAction onClick={() => handleDelete(product.id)}>
                            Delete
                          </AlertDialogAction>
                        </AlertDialogFooter>
                      </AlertDialogContent>
                    </AlertDialog>
                  </div>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </div>
    </AdminLayout>
  );
};

export default AdminProducts;
