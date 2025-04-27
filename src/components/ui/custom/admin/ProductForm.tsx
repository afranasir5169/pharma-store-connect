
import React, { useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Switch } from "@/components/ui/switch";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { useToast } from "@/hooks/use-toast";
import { Upload, X } from "lucide-react";

interface ProductFormProps {
  initialData?: {
    id?: number;
    name: string;
    price: number;
    discountPrice?: number;
    description: string;
    category: string;
    sku: string;
    requiresPrescription: boolean;
    stock: number;
  };
  onSubmit: (data: any) => void;
}

const ProductForm = ({ initialData, onSubmit }: ProductFormProps) => {
  const { toast } = useToast();
  const isEditing = !!initialData?.id;
  
  const [formData, setFormData] = useState({
    name: initialData?.name || "",
    price: initialData?.price || 0,
    discountPrice: initialData?.discountPrice || 0,
    description: initialData?.description || "",
    category: initialData?.category || "",
    sku: initialData?.sku || "",
    requiresPrescription: initialData?.requiresPrescription || false,
    stock: initialData?.stock || 0,
  });
  
  const [images, setImages] = useState<string[]>([]);
  
  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value,
    }));
  };
  
  const handleNumberChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: parseFloat(value) || 0,
    }));
  };
  
  const handleSwitchChange = (checked: boolean) => {
    setFormData(prev => ({
      ...prev,
      requiresPrescription: checked,
    }));
  };
  
  const handleCategoryChange = (value: string) => {
    setFormData(prev => ({
      ...prev,
      category: value,
    }));
  };
  
  const handleImageUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files.length > 0) {
      const newImages = Array.from(e.target.files).map(file => URL.createObjectURL(file));
      setImages(prev => [...prev, ...newImages]);
    }
  };
  
  const handleRemoveImage = (index: number) => {
    setImages(prev => prev.filter((_, i) => i !== index));
  };
  
  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    // Validation
    if (!formData.name || !formData.price || !formData.category) {
      toast({
        title: "Validation Error",
        description: "Please fill in all required fields.",
        variant: "destructive",
      });
      return;
    }
    
    // Submit form data with images
    onSubmit({
      ...formData,
      images,
    });
    
    toast({
      title: isEditing ? "Product Updated" : "Product Created",
      description: `${formData.name} has been successfully ${isEditing ? "updated" : "created"}.`,
    });
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="space-y-4">
          <div className="space-y-2">
            <Label htmlFor="name">Product Name *</Label>
            <Input
              id="name"
              name="name"
              value={formData.name}
              onChange={handleChange}
              required
            />
          </div>
          
          <div className="space-y-2">
            <Label htmlFor="price">Price *</Label>
            <Input
              id="price"
              name="price"
              type="number"
              step="0.01"
              value={formData.price}
              onChange={handleNumberChange}
              required
            />
          </div>
          
          <div className="space-y-2">
            <Label htmlFor="discountPrice">Discount Price</Label>
            <Input
              id="discountPrice"
              name="discountPrice"
              type="number"
              step="0.01"
              value={formData.discountPrice}
              onChange={handleNumberChange}
            />
          </div>
          
          <div className="space-y-2">
            <Label htmlFor="stock">Stock *</Label>
            <Input
              id="stock"
              name="stock"
              type="number"
              value={formData.stock}
              onChange={handleNumberChange}
              required
            />
          </div>
          
          <div className="space-y-2">
            <Label htmlFor="sku">SKU</Label>
            <Input
              id="sku"
              name="sku"
              value={formData.sku}
              onChange={handleChange}
            />
          </div>
        </div>
        
        <div className="space-y-4">
          <div className="space-y-2">
            <Label htmlFor="category">Category *</Label>
            <Select
              value={formData.category}
              onValueChange={handleCategoryChange}
              required
            >
              <SelectTrigger id="category">
                <SelectValue placeholder="Select category" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="vitamins">Vitamins & Supplements</SelectItem>
                <SelectItem value="medications">Medications</SelectItem>
                <SelectItem value="firstaid">First Aid</SelectItem>
                <SelectItem value="skincare">Skin Care</SelectItem>
                <SelectItem value="devices">Medical Devices</SelectItem>
              </SelectContent>
            </Select>
          </div>
          
          <div className="space-y-2">
            <Label htmlFor="description">Description *</Label>
            <Textarea
              id="description"
              name="description"
              rows={5}
              value={formData.description}
              onChange={handleChange}
              required
            />
          </div>
          
          <div className="flex items-center space-x-2">
            <Switch
              id="requiresPrescription"
              checked={formData.requiresPrescription}
              onCheckedChange={handleSwitchChange}
            />
            <Label htmlFor="requiresPrescription">Requires Prescription</Label>
          </div>
        </div>
      </div>
      
      <div className="space-y-4">
        <div>
          <Label>Product Images</Label>
          <div className="mt-2 flex items-center space-x-4">
            <Button
              type="button"
              variant="outline"
              onClick={() => document.getElementById("image-upload")?.click()}
              className="flex items-center space-x-2"
            >
              <Upload className="h-4 w-4" />
              <span>Upload Images</span>
            </Button>
            <Input
              id="image-upload"
              type="file"
              accept="image/*"
              multiple
              className="hidden"
              onChange={handleImageUpload}
            />
            <p className="text-sm text-gray-500">Upload up to 5 images</p>
          </div>
        </div>
        
        {images.length > 0 && (
          <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            {images.map((image, index) => (
              <div key={index} className="relative border rounded-md overflow-hidden">
                <img src={image} alt={`Product ${index + 1}`} className="w-full h-24 object-cover" />
                <button
                  type="button"
                  className="absolute top-1 right-1 bg-white rounded-full p-1 hover:bg-gray-100"
                  onClick={() => handleRemoveImage(index)}
                >
                  <X className="h-4 w-4" />
                </button>
              </div>
            ))}
          </div>
        )}
      </div>
      
      <div className="flex justify-end">
        <Button type="submit" className="bg-primary hover:bg-primary/90">
          {isEditing ? "Update Product" : "Create Product"}
        </Button>
      </div>
    </form>
  );
};

export default ProductForm;
