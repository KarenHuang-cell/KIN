import os, http.server, socketserver
os.chdir('/Users/karenhuang/Documents/Projects/KIN/Claude/2026')
Handler = http.server.SimpleHTTPRequestHandler
with socketserver.TCPServer(("", 3001), Handler) as httpd:
    print("Serving on port 3001")
    httpd.serve_forever()
